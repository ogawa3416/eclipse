package sample20_11;
public class Exec {
	public static void main(String[] args) {
		System.out.println("args[0]=" + args[0]); // 0番目の引数を表示
		int val = Integer.parseInt(args[1]); // 1番目の引数を整数に変換
		System.out.println("args[1]=" + val); // 1番目の引数を表示
	}
}
