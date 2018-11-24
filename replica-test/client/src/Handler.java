import java.io.IOException;
import java.io.PrintWriter;
import java.util.ArrayList;
import java.util.LinkedList;
import java.util.List;

import javax.swing.SwingWorker;

import org.knowm.xchart.SwingWrapper;
import org.knowm.xchart.XYChart;

class Handler extends SwingWorker<Boolean, double[]> {
	
	public static String OUTPUT_FILE_NAME = "replica-test.tsv"; 

	private int gatherMillis;
	private final XYChart chart;
	private final SwingWrapper<XYChart> wrapper;
	private List<Long> buffer = new ArrayList<Long>();
	private LinkedList<Double> fifo = new LinkedList<Double>();
	private PrintWriter writer;

	public Handler(XYChart chart, int gatherMillis) throws IOException {
		this.chart = chart;
		this.gatherMillis = gatherMillis;

		wrapper = new SwingWrapper<XYChart>(chart);
		wrapper.displayChart();
		
		writer = new PrintWriter(OUTPUT_FILE_NAME, "UTF-8");
		writer.println("ttime");

		fifo.add(0.0);
	}

	@Override
	protected Boolean doInBackground() throws Exception {
		while (!isCancelled()) {
			long deadline = System.currentTimeMillis() + gatherMillis;
			long sum = 0;
			int count = 0;

			// Read results
			while (System.currentTimeMillis() < deadline) {
				Long result = Worker.resultQ.poll(); 
				if (result == null) {
					Thread.sleep(10);
					continue;
				}
				writer.println(result);
				buffer.add(result);
				sum += result;
				count += 1;
			}
			writer.flush();
			
			// Add to chart
			if (count != 0) {
				fifo.add((double)sum / count);
				if (fifo.size() > 100) {
					fifo.removeFirst();
				}
			}

			// Publish
			double[] array = new double[fifo.size()];
			for (int i = 0; i < fifo.size(); i++)
				array[i] = fifo.get(i);
			publish(array);
		}

		writer.close();
		return true;
	}

	@Override
	protected void process(List<double[]> chunks) {
		double[] mostRecentDataSet = chunks.get(chunks.size() - 1);
		chart.updateXYSeries("Benchmark", null, mostRecentDataSet, null);
		wrapper.repaintChart();

		long start = System.currentTimeMillis();
		long duration = System.currentTimeMillis() - start;
		try {
			Thread.sleep(40 - duration); // 40 ms ==> 25fps
		} catch (InterruptedException e) {
			System.err.println("Error: " + e.getMessage());
			System.exit(1);
		}
	}

}
