package sample20_09;
import java.util.Arrays;
public class Exec1 {
	public static void main(String[] args) {
		int[][] numbers = { { 1, 2, 3 }, { 4, 5, 6 }, { 7, 8, 9 } };
		for (int[] array : numbers) {
			System.out.print(Arrays.toString(array) + "\t");
		}
	}
}
