package exercise;

public class Pass07_01 {

	public static void main(String[] args) {
		double[] x = {12.3, 13.5, 11.5, 13.0, 12.8, 12.5};
		
		double total=0;
		
		for (double a : x) {
			total += a;
		}
		
		System.out.println("合計=" + total);
		System.out.println("平均=" + (total / x.length));
		
		for(double a : x) {
			System.out.print(a + "\t");
		}

	}

}
