package sample15_03;
import java.util.Arrays;
import lib.Input;
public class Exec {
	public static void main(String[] args) {
		double[] data = new double[5];
		for(int i=0; i<data.length; i++){
			data[i] = Input.getDouble("値");
		}
		String	list = Arrays.toString(data);
		System.out.println(list);
	}
}
