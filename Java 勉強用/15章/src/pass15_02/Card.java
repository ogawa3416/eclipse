package pass15_02;
/**
 * トランプのカードを表すクラス。カードは種類番号（０～３）と札番号の二つのフィールドを持つ。
 * また、種類番号×１３＋札番号をカード番号という。カードの並べ替えにはカード番号をキーとして使う。
 *
 * @author t.kawaba
 *
 */
public class Card {
	// フィールド
	private int	suit;		
	private int	number;

	// コンストラクタ
	/**
	 * 種類と札番号を指定するコンストラクタ。
	 * @param suit		種類番号(０=スぺード、１=ハート、２=クラブ、３=ダイヤ)
	 * @param number	札番号１～１３)
	 */
	public	Card(int suit, int number){
		this.suit 	= suit;
		this.number	= number;
	}
	/**
	 *  カード番号を指定するコンストラクタ。
	 *  カード番号は１から５２（１３×４種＝５２）の番号。
	 * @param a		カード番号（１～５２)
	 */
	public	Card(int a){
		suit 	= (a-1)/13;
		number	= (a-1)%13+1;
	}
	// メソッド
	/**
	 * トランプの種類番号を返す。
	 * @return	種類番号(０～３)
	 */
	public int getSuit() 		{ return suit;	}
	/**
	 * トランプの札番号を返す。
	 * @return	札番号（１～１３）
	 */
	public int getNumber() 	{ return number; }
	/** 
	 * トランプの種類番号に対応する種類名文字列を返す。
	 * @return	種類番号に対応して次のような文字列を返す
	 * 			０=スぺード、１=ハート、２=クラブ、３=ダイヤ
	 */
	public	String getSuitString(){
		String[] name = {"スペード：","ハート　：","クラブ　：","ダイヤ　："};
		return	name[suit];
	}
	/**
	 * 札番号を2桁の文字列に直して返す。
	 * (１桁の数値は"　５"のように前に半角空白をひとつ付ける）
	 * 
	 * 文字列の連結により、数値を文字列に直している。
	 * @return ２桁の文字列にした札番号
	 */
	public String numberString(){
		return number<10 ? " "+number : ""+number;			
	}
	/**
	 * トランプの種類番号と札番号からカード番号ｎを計算して返す。
	 *	ｎ＝１３×種類番号＋札番号
	 * @return	カード番号
	 */
	public	int	seqNumber()		{ return 13*suit + number; }
	/**
	 * トランプカードの内容を表す文字列を返す。
	 * @return	種類名と札番号からなる文字列
	 */
	public	String	toString()	{ return getSuitString()+numberString(); }
	/**
	 *  引数のカードが同じかどうかを調べる。同じとは、種類と札番号が同じであることを言う。 
	 *  
	 */
	public	boolean	equals(Object o){
		if((o instanceof Card) && (((Card)o).number==number) && ((Card)o).suit==suit)	return	true;
		else	return	false;
	}
	/**
	 * ハッシュコードを返す
	 */
	public	int	hashCode(){
		int	h = 17;
		h = 31*h + suit;
		h = 31*h + number;
		return	h;
	}
}
