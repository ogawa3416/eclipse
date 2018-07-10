package exercise;

import lib.Input;

public class Ex10_02_1 {

	public static void main(String[] args) {
		int nin=0;
		nin = Input.getInt("人数");
		int ryoukin = nin * 850;
		
		if(nin>= 5) {
			ryoukin *= 0.7;
		}
		
		System.out.println("入館料は" + ryoukin + "円です");

	}

}
