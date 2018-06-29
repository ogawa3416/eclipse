package sample19_04;
public class Member implements Responsible{
	private int	id;
	private String name;
	public Member(int id, String name) {
		this.id	  = id;
		this.name = name;
	}
	@Override
	public String info() {
		return "Member ver1.0";
	}
	@Override
	public String exp() {
		return "フィットネスクラブの一般会員のクラス";
	}	
	public int getId() {
		return id;
	}
	public String getName() {
		return name;
	}
}
