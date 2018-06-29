package sample13_03;

public class Random {

	public static void main(String[] args) {
		for(int i=0; i<10; i++){
			double rand = Math.random();
			System.out.print(rand);
			System.out.print("\t"+ rand*6);
			System.out.print("\t"+ (int)(rand*6));
			System.out.print("\t"+ ((int)(rand*6) + 1));
			System.out.println();
			
		}
	}

}
