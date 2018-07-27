package ex16_01_1;

public class Card {
	private String suit; // カードの種類 "スペード"、"ハート"、"クラブ"、"ダイヤ"
	private int number; // カードの札番号 1～13

	public Card(String suit, int number) {
		this.suit = suit;
		this.number = number;
	}

	String face() { // カードを表す文字列を返す
		return suit + "/" + number;
	}
	public static void main(String[] args){
		Card card = new Card("スペード", 10);
		System.out.println(card.face());
	}
}
