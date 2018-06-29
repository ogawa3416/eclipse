package appendix;
import lib.Input;
public class Exec {
	private static final String[] NAMES = {"ノリ","はさみ","定規"};	// 道具の名前の配列
	public static void main(String[] args){
		int id = Input.getInt();
		System.out.print(NAMES[id]);
	}
}
