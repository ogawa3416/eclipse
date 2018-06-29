package exercise;

public class Pass06_03 {

	public static void main(String[] args) {
		
		String[] name = {"田中", "青木", "松田", "斎藤", "鈴木"} ;
		int[] year = {22, 19, 24, 26,18} ;
		double[] height = {175.5, 183.2, 170.5, 165.3, 185.0} ;
		
		for (int i=0; i<5; i++) {
			System.out.println(name[i] + "\t" + year[i] + "\t" + height[i] + "\t");
		}

	}

}
