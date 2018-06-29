package exercise;

public class Ex07_02_1 {

	public static void main(String[] args) {
		double[] val = {188.2, 175.6, 154.5, 168.2, 178.0} ;
		
		for (int i=0; i<val.length; i++) {
			System.out.print(val[i] + "\t");
		}
		
		double total = 0;
		
		for (int i=0; i<val.length; i++) {
			total += val[i];
		}
		double mean = (total / val.length);
		
		for (int i=0; i<val.length; i++) {
			System.out.println(Math.pow(val[i]-mean,2));
		}

	}

}
