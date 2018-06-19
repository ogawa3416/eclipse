package com.marineroad.springboot;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.servlet.ModelAndView;

@Controller // (1)
public class ThymeleafController {

	/**
	 * Thymeleaf 基本編
	 *
	 * @param mav
	 *            ModelAndViewクラス テンプレートで利用するデータ類とビューに関する情報をまとめて管理するクラス
	 * @return
	 */
	@RequestMapping("/boot")
	public ModelAndView index(ModelAndView mav) {

		// 1.(1) 変数式
		// ・コントローラーからテンプレートに値を渡す
		// ・変数「msg」に値を設定
		mav.addObject("msg", "コントローラーからテンプレートのここに値を渡す");

		// 使用するビューを設定
		mav.setViewName("index"); // (3)

		return mav;
	}

}