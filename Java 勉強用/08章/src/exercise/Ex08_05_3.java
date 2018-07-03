package exercise;

import lib.Input;

public class Ex08_05_3 {

	public static void main(String[] args) {
		int year = Input.getInt("year");
		
		boolean b1 =((year % 4 ==0) && (year %100 != 0)) || (year % 400 ==0);
		
		System.out.println(b1);

	}

}
