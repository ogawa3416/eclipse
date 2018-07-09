package exercise;

import lib.Input;

public class Ex08_07_1 {

	public static void main(String[] args) {
		int year = Input.getInt("year");
		
		String b1 =((year % 4 ==0) && (year %100 != 0)) || (year % 400 ==0) ? "うるう年です" : "平年です";
		System.out.println(b1);

	}

}
