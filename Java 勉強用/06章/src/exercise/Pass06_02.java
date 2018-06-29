package exercise;
import lib.Input;

public class Pass06_02 {
	
	public static void main(String[] args) {
		System.out.println("start");
		
		int m,n;
		

		
		for (int i=0; i<3; i++) {
			m = Input.getInt();
			n = Input.getInt();
			System.out.println(n % m);
		}
		
		System.out.println("end");
	}

}
