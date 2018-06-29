package sample;
/**
 * サイコロをシミュレートするクラス。
 * @author kawaba
 *
 */
public class Dice {
	/**
	 * 現在の目数
	 */
	int		val;	// 目数
	/**
	 * 目数の最大値
	 */
	int		max;	// 最大値
	/**
	 * 目数の最大値と現在の目数を引数に取るコンストラクタ
	 * @param max	目数の最大値
	 * @param val	現在の目数
	 */
	public	Dice(int max, int val){
		this.max = max;
		this.val = val;
	}
	/**
	 * 目数の最大値だけを引数に取るコンストラクタ。目数は１にセットされる
	 * @param max	最大値
	 */
	public	Dice(int max){
		this(max, 1);
	}
	/**
	 * 引数のないコンストラクタ。最大値は６、目数は１にセットされる
	 */
	public	Dice(){
		this(6, 1);
	}
	/**
	 * ランダムに目数を変更する（サイコロを振る）
	 * @return	変更後の目数
	 */
	public int	play(){
		val = (int)(Math.random()*max) + 1;
		return	val;
	}
	/**
	 * 現在の目数を返す
	 * @return	目数（1～max）
	 */
	public int getVal() {
		return val;
	}
	/**
	 * 目数をセットする。
	 * 範囲外の目数の場合何もセットしない
	 * @param val 目数
	 */
	public void setVal(int val) {
		if(val>max || val<0) return;
		this.val = val;
	}
	/**
	 * 目数の最大値を返す
	 * @return　最大値
	 */
	public int getMax() {
		return max;
	}
	/**
	 * 目数の最大値をセットする。
	 * 0または負の数の場合なにもセットしない。
	 * @param max 	最大値
	 */
	public void setMax(int max) {
		if(max<=0)	return;
		this.max = max;
	}
}

