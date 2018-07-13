package exercise;

public class Ex11_03_1 {

	public static void main(String[] args) {
		double[] data = {2.5, 3.3, 7.0, -4.5, 5.2};
		int i;
		for(double x : data) {
			if(x<0) {
				System.out.println("不正なデータ:" + x);
				break;
			}
			System.out.println(Math.sqrt(x));
		}
			

	}

}
