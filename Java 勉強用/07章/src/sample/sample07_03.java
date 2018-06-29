package sample;

public class sample07_03 {

	public static void main(String[] args) {
		int[] data = {10, 20, 30} ;
		int total = 0;
		for(int i=0; i<data.length; i++) {
			total += data[i];
		}
		
		System.out.println("合計=" + total);

	}

}
