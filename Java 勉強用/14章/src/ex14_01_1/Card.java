package ex14_01_1;

public class Card {
	String suit; // カードの種類 "スペード"、"ハート"、"クラブ"、"ダイヤ"
	int number; // カードの札番号 1～13
	
	public Card(String suit, int number) {
		this.suit = suit;
		this.number = number;
	}
	
	String face() { // カードを表す文字列を返す
		return suit + "/" + number;
	}
}
