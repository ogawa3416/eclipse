package sample;

public class sample07_04 {

	public static void main(String[] args) {
		double[] val = {1.25, 0.85, 3.2, 4.11, 0.56, 7.6} ;
		double total = 0;
		
		for(int i=0; i<val.length; i++) {
			total += val[i];
		}
		System.out.println("合計=" + total);
		
		for(int i=0; i<val.length; i++) {
			System.out.print(val[i] + "\t");
		}

	}

}
