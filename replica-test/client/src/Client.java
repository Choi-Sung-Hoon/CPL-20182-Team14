import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.net.Socket;
import java.util.ArrayList;
import java.util.LinkedList;
import java.util.List;
import java.util.Scanner;
import java.util.concurrent.ConcurrentLinkedQueue;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

import javax.swing.SwingWorker;

import org.knowm.xchart.QuickChart;
import org.knowm.xchart.SwingWrapper;
import org.knowm.xchart.XYChart;

/**
 * Creates a real-time chart using SwingWorker
 */
public class Client
{
	MySwingWorker mySwingWorker;
	SwingWrapper<XYChart> sw;
	XYChart chart;
	static double elapsedTime = 0.0;
	
	static private Worker[] workers;
	
	public static void main(String[] args) throws Exception
	{
		Scanner scanner = new Scanner(System.in);
		String hostname = "localhost";
		int port = Integer.parseInt(args[0]);
		long startTime, stopTime;
		
		int pool_cnt=Integer.parseInt(args[1]);
		ExecutorService executorService=Executors.newFixedThreadPool(pool_cnt);
		
		ConcurrentLinkedQueue<Integer> taskQ=new ConcurrentLinkedQueue<>();
		for(int i=1; i<=1000; i+= 10)
			taskQ.offer(i);
		
		workers = new Worker[pool_cnt];
		for(int i=0; i<pool_cnt; i++)
		{
			workers[i] = new Worker(taskQ, hostname, port);
			executorService.execute(workers[i]);
		}
		
		
		// create a graph
		Client swingWorkerRealTime = new Client();
		swingWorkerRealTime.go();
		
				
		//System.out.println("Client End.");
		//scanner.close();
		executorService.shutdown();
	}

	private void go()
	{
		// Create Chart
		chart = QuickChart.getChart("Realtime graph", "Time", "Value", "Benchmark", new double[] { 0 },
				new double[] { 0 });
		chart.getStyler().setLegendVisible(false);
		chart.getStyler().setXAxisTicksVisible(false);
		chart.getStyler().setXAxisMax(100.0);
		chart.getStyler().setXAxisMin(0.0);
		chart.getStyler().setYAxisMax(1000.0);
		chart.getStyler().setYAxisMin(0.0);

		// Show it
		sw = new SwingWrapper<XYChart>(chart);
		sw.displayChart();

		mySwingWorker = new MySwingWorker();
		mySwingWorker.execute();
	}

	private class MySwingWorker extends SwingWorker<Boolean, double[]>
	{
		LinkedList<Double> fifo = new LinkedList<Double>();

		public MySwingWorker()
		{
			fifo.add(0.0);
		}

		@Override
		protected Boolean doInBackground() throws Exception
		{
			while (!isCancelled())
			{
				fifo.add((double)Worker.resultQ.peek());
				
				if (fifo.size() > 100)
					fifo.removeFirst();

				double[] array = new double[fifo.size()];
				for (int i = 0; i < fifo.size(); i++)
					array[i] = fifo.get(i);
				publish(array);

				try
				{
					Thread.sleep(100);
				}
				catch (InterruptedException e)
				{
					System.out.println("Error : " + e.getMessage());
					System.exit(1);
				}
			}

			return true;
		}

		@Override
		protected void process(List<double[]> chunks)
		{
			//System.out.println("number of chunks: " + chunks.size());
			
			double[] mostRecentDataSet = chunks.get(chunks.size() - 1);
			chart.updateXYSeries("Benchmark", null, mostRecentDataSet, null);
			sw.repaintChart();

			long start = System.currentTimeMillis();
			long duration = System.currentTimeMillis() - start;
			try
			{
				Thread.sleep(40 - duration); // 40 ms ==> 25fps
			}
			catch (InterruptedException e)
			{
				System.out.println("Error : " + e.getMessage());
				System.exit(1);
			}
		}
	}
}