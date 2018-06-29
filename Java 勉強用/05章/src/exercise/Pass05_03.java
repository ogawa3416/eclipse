package exercise;
import lib.Input;

public class Pass05_03 {

	public static void main(String[] args) {
		double a = Input.getDouble();
		double b = Input.getDouble();
		double c = Input.getDouble();
		
		double ans1 = Math.pow(a , 2) + Math.pow(b , 2) - (2 * c) ;
		double ans2 = Math.sqrt(a) + Math.sqrt(b) + (3 * c) ;
		
		System.out.println("(1)=" + ans1);
		System.out.println("(2)=" + ans2);

	}

}
