package cdp1.kube.testserver;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.net.Socket;

class ServerSession extends Thread {

	private final Socket mSocket;
	
	public ServerSession(Socket socket) {
		mSocket = socket;
	}
	
	public void run() {
		try (Socket socket = mSocket) {
			InputStream input = socket.getInputStream();
			BufferedReader reader = new BufferedReader(new InputStreamReader(input));
			
			OutputStream output = socket.getOutputStream();
			PrintWriter writer = new PrintWriter(output, true);
			
			String text;
			int n, prime;
			
			text = reader.readLine();
			n = Integer.parseInt(text);
			prime = new ServerLogic(n).getNthPrimeNumber();
			writer.println(Integer.toString(prime));
		} catch (Exception e) {
			System.err.println("Session exception: " + e.getMessage());
			System.err.flush();
			e.printStackTrace();
		}
	}
	
}
