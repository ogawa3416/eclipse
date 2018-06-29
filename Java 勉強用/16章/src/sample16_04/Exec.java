package sample16_04;
import static lib.Input.getDouble;
import static java.lang.Math.pow;
import static java.lang.Math.PI;
public class Exec {
	public static void main(String[] args) {
		double hankei = getDouble("半径"); 				// 半径を入力
		System.out.println("面積=" + pow(hankei, 2) * PI); // 面積を計算して表示
	}
}
