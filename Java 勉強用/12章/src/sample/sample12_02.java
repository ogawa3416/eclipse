package sample;

public class sample12_02 {

	public static void main(String[] args) {
		System.out.println("*** BMIを計算 ***");
		dispBmi(1.75, 70.0);
		System.out.println("*** END ***");

	}
	public static void dispBmi(double height, double weight) {
		double bmi = weight / Math.pow(height, 2);
		System.out.println("BMIは" + bmi + "です");
		return;
	}

}
