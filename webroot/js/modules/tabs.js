/**
 * @project       tusk
 * @author        carsten.coull@swu.de
 * @build         Fri, Nov 17, 2023 2:55 PM ET
 * @release       a433e330c30db45ed9eaf70ee5d4ebbf88e92350 [main]
 * @copyright     Copyright (c) 2023, SWU Stadtwerke Ulm / Neu-Ulm GmbH
 *
 */
export default class Tabs{constructor(t){this.main=t,this.main.debug,this.Config={tabGroupSelector:".tab-group",tabButtonSelector:"[data-target]",tabButtonClass:"tab-button",activeTabClass:"tab--active",activeButtonClass:"tab-button--active"},this.tabGroups=document.querySelectorAll(this.Config.tabGroupSelector),this.tabGroups.length>0&&this.setup()}setup(){this.tabGroups.forEach((t=>{let e=t.querySelector(this.Config.tabButtonSelector);this.open(e)}));const t=location.hash.substring(1);if(t){let e=document.querySelector("."+this.Config.tabButtonClass+this.Config.tabButtonSelector.slice(0,-1)+"="+t+"]");if(e){let t=e.closest(this.Config.tabGroupSelector);this.toggle(e,t)}}}init(){this.tabGroups.forEach((t=>{t.querySelectorAll(this.Config.tabButtonSelector).forEach((e=>{e.addEventListener("click",(s=>{s.preventDefault(),this.toggle(e,t)}))}))})),window.addEventListener("layout-update",(()=>this.refresh()))}toggle(t,e){this.close(e),this.open(t)}close(t){let e=t.querySelectorAll("."+this.Config.activeButtonClass),s=t.querySelectorAll("."+this.Config.activeTabClass);e.forEach((t=>{t.classList.remove(this.Config.activeButtonClass)})),s.forEach((t=>{t.classList.remove(this.Config.activeTabClass)}))}open(t){let e=document.getElementById(t.dataset.target);t.classList.add(this.Config.activeButtonClass),e.classList.add(this.Config.activeTabClass)}refresh(){this.tabGroups=document.querySelectorAll(this.Config.tabGroupSelector),this.tabGroups.forEach((t=>{let e=t.querySelector(this.Config.tabButtonSelector);this.open(e)}))}}