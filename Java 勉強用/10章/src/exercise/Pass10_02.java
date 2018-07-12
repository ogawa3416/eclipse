package exercise;

import lib.Input;

public class Pass10_02 {

	public static void main(String[] args) {
		double dist;
		int ryoukin = 0;
		
		dist = Input.getDouble();
		
		if(dist < 50) {
			ryoukin = 300;
		}else if(dist < 100) {
			ryoukin = 500;
		}else if(dist < 500) {
			ryoukin = 700;
		}else {
			ryoukin = 1000;
		}
		
		System.out.println("料金=" + ryoukin + "円");

	}

}
