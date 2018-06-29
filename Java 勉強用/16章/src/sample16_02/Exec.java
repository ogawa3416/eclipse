package sample16_02;
class	Card {
	private	String suit;
	private	int	number;
	public	Card(String suit, int number){
		this.suit   = suit;
		this.number = number;
	}
	public String getSuit() {
		return suit;
	}
	public int getNumber() {
		return number;
	}
}
public class Exec {
	public static void main(String[] args) {
		Card card = new Card("スペード", 1);
		System.out.println(card.getSuit()+"/"+card.getNumber());
	}
}
