package exercise;

public class Ex07_01_1 {

	public static void main(String[] args) {
		double[] x = {167.5, 180.3, 177.6, 166.7, 182.5, 174.4} ;
		
		double total =0;
		
		for (int i=0; i<x.length; i++) {
			total += x[i];
		}
		
		System.out.println("合計=" + total);
		System.out.println("平均=" + total / x.length);

	}

}
