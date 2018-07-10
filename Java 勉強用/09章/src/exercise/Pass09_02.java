package exercise;

import lib.Input;

public class Pass09_02 {

	public static void main(String[] args) {
		double value, total=0;
		int kensu=0;
		
		while((value = Input.getDouble()) !=0) {
			total += value;
			kensu++;
		}
		
		System.out.println("合計=" + total);
		System.out.println("件数=" + kensu);
		System.out.println("平均=" + total/kensu);

	}

}
