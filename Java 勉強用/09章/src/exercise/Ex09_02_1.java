package exercise;

import lib.Input;

public class Ex09_02_1 {

	public static void main(String[] args) {
		double x;
		
		while((x = Input.getDouble()) != 0) {
			System.out.println(Math.sqrt(x));
		}

	}

}
