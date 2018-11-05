package cdp1.kube.testserver;

import java.util.Scanner;

class ServerLogic {
	int nth;
	
	public ServerLogic(int n)
	{
		this.nth = n;
	}

	int getNthPrimeNumber()
	{
		int cnt=0;
		int number=2;
		
		while(true)
		{
			if(isPrimeNumber(number))
			{
				cnt++;
				if(cnt==nth)
					break;
			}
			
			number++;
		}
		
		return number;
	}
	
	boolean isPrimeNumber(int n)
	{
		boolean isPrime = true;
		int i = 2;
		
		while(i!=n)
		{
			if(n%i==0)
			{
				isPrime = false;
			}
			
			i++;
		}
		
		return isPrime;
	}
	
	/*public static void main(String[] args)
	{
		ServerLogic test;
		int n;
		int primeN;
		
		Scanner scan = new Scanner(System.in);
		System.out.println("get Nth prime number : ");
		n = scan.nextInt();
		test = new ServerLogic(n);
		primeN = test.getNthPrimeNumber();
		System.out.println("nth Prime number : " + primeN);
	}*/
}
