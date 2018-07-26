package Ex15_03_1;
import java.util.Arrays;

import lib.Input;
public class Exec {
	public static void main(String[] args) {
		String[] names = new String[5];
		for(int i=0; i<names.length; i++){
			names[i] = Input.getString("名前");
		}
		System.out.println(Arrays.toString(names));
		

	}
}
