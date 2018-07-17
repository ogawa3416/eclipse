package exercise;

import lib.Input;

public class Ex12_03_1 {

	public static void main(String[] args) {
		int tanka = Input.getInt("単価");
		double ritu = Input.getDouble("税率");		
		System.out.println("税額=" + tax(tanka, ritu));

	}
	public static int tax(int tanka, double ritu) {
		return (int)(tanka * ritu);
	}

}
