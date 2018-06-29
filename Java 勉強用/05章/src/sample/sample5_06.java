package sample;

public class sample5_06 {

	public static void main(String[] args) {
		String ss = "abAB日本語ａｂＡＢ";
		
		int len = ss.length();
		int pos = ss.indexOf('日');
		char ch = ss.charAt(4);
		String str = ss.toLowerCase();
		
		System.out.println("長さ=" + len);
		System.out.println("\'日\'は " + pos + "番目");
		System.out.println("4番目の文字は " + ch);
		System.out.println( ss + "⇒" + str);


	}

}
