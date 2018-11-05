package cdp1.kube.testserver;

import java.net.ServerSocket;
import java.net.Socket;

class Listener implements Runnable {

	private final int mPort;
	private final boolean mMultiThreaded;

	public Listener(int port, boolean multiThreaded) {
		mPort = port;
		mMultiThreaded = multiThreaded;
	}

	@Override
	public void run() {
		System.out.println("Server multithread mode: ");
		
		try (ServerSocket serverSocket = new ServerSocket(mPort)) {
			System.out.println("Server is listening on port " + mPort);

			while (true) {
				Socket socket = serverSocket.accept();
				System.out.println("New client connected");

				ServerSession session = new ServerSession(socket);
				session.start();
				
				// Synchronous tasking
				if (!mMultiThreaded) {
					session.wait();
				}
			}

		} catch (Exception e) {
			System.err.println("Server exception: " + e.getMessage());
			System.err.flush();
			e.printStackTrace();
		}
	}

	public static void main(String[] args) {
		int port = 0;
		boolean multithreaded = true;

		System.out.println("Starting server...");

		// Get port number
		try {
			port = Integer.parseInt(args[0]);
		} catch (ArrayIndexOutOfBoundsException e) {
			System.err.println("Please provide the port number as a parameter!");
			System.err.flush();
			System.exit(1);
		} catch (NumberFormatException e) {
			System.err.println("The port number is invalid!");
			System.err.flush();
			System.exit(1);
		}
		
		// Get whether multi-threaded or not
		try {
			multithreaded = Boolean.parseBoolean(args[1]);
		} catch (ArrayIndexOutOfBoundsException e) {
			;
		}

		Listener listener = new Listener(port, multithreaded);
		listener.run();

		System.out.println("Closing server...");
	}

}
