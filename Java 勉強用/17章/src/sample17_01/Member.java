package sample17_01;
public class Member {
	private int	id;
	private String name;
	public Member(int id, String name) {
		this.id	  = id;
		this.name = name;
	}
	public int getId() {
		return id;
	}
	public String getName() {
		return name;
	}


	// 後から追加
	public static void main(String[] args) {
		Member member = new Member(100, "田中宏");
		System.out.println(member.getId()+"/"+ member.getName());
	}
}
