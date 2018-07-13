package exercise;

public class Pass11_02 {

	public static void main(String[] args) {
		int[] val = {10, -12, 5, -12, 12, 25};
		for( int n : val) {
			if(n < 0) {
				System.out.println("負の値です");
				continue;
			}
			
			System.out.println(Math.sqrt(n));
		}

	}

}
