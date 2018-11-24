import java.io.IOException;
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
	Handler handler;
	SwingWrapper<XYChart> sw;
	XYChart chart;
	
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
		for(int i=1; i<=1000; i+= 1)
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
		
		executorService.shutdown();
		
		return;
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
		//sw = new SwingWrapper<XYChart>(chart);
		//sw.displayChart();

		try {
			handler = new Handler(chart, 100);
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		handler.execute();
	}

}