package sample19_04;

public class Information {
	public static	void	print(Responsible res){
		headder(res);
		body(res);
	}
	public	static	void	headder(Responsible res){
		System.out.println(res.info());
	}
	public	static	void	body(Responsible res){
		System.out.println(res.exp());
	}
}
