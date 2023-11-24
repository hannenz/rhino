/**
 * @project       tusk
 * @author        carsten.coull@swu.de
 * @build         Fri, Nov 24, 2023 3:49 PM ET
 * @release       621832333ab48a5893da21f81678e52e712626a5 [main]
 * @copyright     Copyright (c) 2023, SWU Stadtwerke Ulm / Neu-Ulm GmbH
 *
 */
import ThemeSwitcher from"/rhino/js/modules/theme-switcher.js";import Modal from"/rhino/js/modules/modal.js";import Files from"/rhino/js/modules/files.js";import Menu from"/rhino/js/modules/menu.js";import Tabs from"/rhino/js/modules/tabs.js";import FieldOptions from"/rhino/js/modules/field-options.js";import HooksHandler from"/rhino/js/modules/hooks-handler.js";class MAIN{constructor(){this.debug=!1,document.addEventListener("DOMContentLoaded",(()=>this.init())),window.onload=()=>this.main()}init(){this.debug,this._layoutUpdate=new CustomEvent("layout-update",{}),this.Hooks=new HooksHandler(this),this.ThemeSwitcher=new ThemeSwitcher(this),this.FieldOptions=new FieldOptions(this),this.Tabs=new Tabs(this),document.body.classList.add("page-has-loaded"),window.addEventListener("resize",(()=>this.throttle(this.resizeHandler)),{passive:!0}),window.addEventListener("scroll",(()=>this.throttle(this.scrollHandler)),{passive:!0})}layoutUpdate(){window.dispatchEvent(this._layoutUpdate)}main(){this.pageInit(),this.debug,this.ThemeSwitcher.init(),this.FieldOptions.init(),this.Tabs.init(),this.Modal=new Modal(this),this.Menu=new Menu(this),this.Files=new Files(this),document.body.classList.add("page-has-rendered")}pageInit(){if(this.debug,this.header=document.querySelector("header"),this.header){let t=this.header.getBoundingClientRect();this.headerBottom=t.top+t.height}}scrollHandler(){let t=window.scrollY;this.debug,document.body.classList.toggle("has-scrolled",t>0),document.body.classList.toggle("has-scrolled-a-bit",t>30),document.body.classList.toggle("has-scrolled-past-header",t>this.headerBottom),document.body.classList.toggle("has-scrolled-100vh",t>window.innerHeight),this.lastScrollPosition&&(document.body.classList.toggle("has-scrolled-up",t<this.lastScrollPosition),document.body.classList.toggle("has-scrolled-down",t>this.lastScrollPosition)),this.lastScrollPosition=t}resizeHandler(){let t=window.innerWidth,e=window.innerHeight;this.debug,document.body.style.setProperty("--window-width",`${t}px`),document.body.style.setProperty("--window-height",`${e}px`)}throttle(t){this.ticking=!1,this.ticking||(window.requestAnimationFrame((()=>{t.call(this),this.ticking=!1})),this.ticking=!0)}onOutsideClick(t,e){document.addEventListener("click",(s=>{var i=s.target.parentNode.closest(t);null==i&&document.querySelector(t)!=s.target&&e(s,i)}))}}new MAIN;