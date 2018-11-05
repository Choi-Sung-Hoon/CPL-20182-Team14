import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.PrintWriter;
import java.net.Socket;
import java.util.Scanner;

public class Client {
	public static void main(String[] args)
	{
		Scanner scan = new Scanner(System.in);
		String hostname = "localhost";
		int port = 5001;
		String nth = scan.nextLine();
		
		try(Socket socket = new Socket(hostname, port))
		{
			OutputStream output = socket.getOutputStream();
			PrintWriter writer = new PrintWriter(output, true);
			writer.println(nth);
			
			InputStream input = socket.getInputStream();
			BufferedReader reader = new BufferedReader(new InputStreamReader(input));
			
			String line;
			
			while((line=reader.readLine())!=null)
				System.out.println(line);
		}
		catch(Exception e)
		{
			e.printStackTrace();
		}
	}

}
