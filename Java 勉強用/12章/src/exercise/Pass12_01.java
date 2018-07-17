package exercise;

import lib.Input;

public class Pass12_01 {

	public static void main(String[] args) {
		double mile = Input.getDouble("mile");
		double km = mileToKm(mile);
		
		System.out.println(mile + "マイル = " + km + "キロ");
	}
	public static double mileToKm(double mile) {
		double km = mile * 1.609344;
		return km;
	}

}
