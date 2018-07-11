package sample;

import lib.Input;

public class sample10_05 {

	public static void main(String[] args) {
		int sansei=0, hantai=0, data;
		
		while((data = Input.getInt()) !=0) {
			if(data == 1) {
				sansei++;
			}else {
				hantai++;
			}
		}
		
		System.out.println("賛成=" + sansei);
		System.out.println("反対=" + hantai);

	}

}
