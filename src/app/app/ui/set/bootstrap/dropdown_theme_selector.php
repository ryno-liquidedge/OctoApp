<?php

namespace LiquidedgeApp\Octoapp\app\app\ui\set\bootstrap;

/**
 * @package app\ui\set\bootstrap
 * @author Ryno Van Zyl
 * @copyright Copyright Liquid Edge Solutions. All rights reserved.
 */
class dropdown_theme_selector extends \com\ui\intf\element {
	//--------------------------------------------------------------------------------
	// fields
	//--------------------------------------------------------------------------------
	protected static $is_singleton = true;
	//--------------------------------------------------------------------------------
	// magic
	//--------------------------------------------------------------------------------
	protected function __construct($options = []) {
		// init
		$this->name = "Theme Selector Dropdown";
	}
	//--------------------------------------------------------------------------------
	// functions
	//--------------------------------------------------------------------------------
	public function build($options = []) {
		$buffer = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->buffer();

		$dropdown = \LiquidedgeApp\Octoapp\app\app\ui\ui::make()->dropdown([".theme-switcher" => true]);
		$dropdown->add_link("Light", "#", [".dropdown-item d-flex align-items-center active" => true, "icon" => "fa-sun", "@data-bs-theme-value" => "light", "@aria-pressed" => "true"]);
		$dropdown->add_link("Dark", "#", [".dropdown-item d-flex align-items-center active" => true, "icon" => "fa-moon", "@data-bs-theme-value" => "dark", "@aria-pressed" => "true"]);
		$dropdown->add_link("Auto", "#", [".dropdown-item d-flex align-items-center active" => true, "icon" => "star-half-alt", "@data-bs-theme-value" => "auto", "@aria-pressed" => "true"]);
		$buffer->xbutton(false, $dropdown, ["icon" => "fa-sun", "@class" => "btn"]);
		$buffer->script(["*" => "
		
			
			(function() {
	
				// JavaScript snippet handling Dark/Light mode switching
	
				const getStoredTheme = () => localStorage.getItem('theme');
				const setStoredTheme = theme => localStorage.setItem('theme', theme);
				const forcedTheme = document.documentElement.getAttribute('data-bss-forced-theme');
	
				const getPreferredTheme = () => {
	
					if (forcedTheme) return forcedTheme;
	
					const storedTheme = getStoredTheme();
					if (storedTheme) {
						return storedTheme;
					}
	
					const pageTheme = document.documentElement.getAttribute('data-bs-theme');
	
					if (pageTheme) {
						return pageTheme;
					}
	
					return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
				}
	
				const setTheme = theme => {
					if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
						document.documentElement.setAttribute('data-bs-theme', 'dark');
					} else {
						document.documentElement.setAttribute('data-bs-theme', theme);
					}
				}
	
				setTheme(getPreferredTheme());
	
				const showActiveTheme = (theme, focus = false) => {
					const themeSwitchers = [].slice.call(document.querySelectorAll('.theme-switcher'));
	
					if (!themeSwitchers.length) return;
	
					document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
						element.classList.remove('active');
						element.setAttribute('aria-pressed', 'false');
					});
	
					for (const themeSwitcher of themeSwitchers) {
	
						const btnToActivate = themeSwitcher.querySelector('[data-bs-theme-value=\"' + theme + '\"]');
	
						if (btnToActivate) {
							btnToActivate.classList.add('active');
							btnToActivate.setAttribute('aria-pressed', 'true');
						}
					}
				}
	
				window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
					const storedTheme = getStoredTheme();
					if (storedTheme !== 'light' && storedTheme !== 'dark') {
						setTheme(getPreferredTheme());
					}
				});
	
				window.addEventListener('DOMContentLoaded', () => {
					showActiveTheme(getPreferredTheme());
	
					document.querySelectorAll('[data-bs-theme-value]')
						.forEach(toggle => {
							toggle.addEventListener('click', (e) => {
								e.preventDefault();
								const theme = toggle.getAttribute('data-bs-theme-value');
								setStoredTheme(theme);
								setTheme(theme);
								showActiveTheme(theme);
							})
						})
				});
			})();
		
		"]);
		return $buffer->build();
	}
	//--------------------------------------------------------------------------------
}