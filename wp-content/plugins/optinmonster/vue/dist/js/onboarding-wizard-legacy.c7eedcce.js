"use strict";(self["webpackChunkoptinmonster_wordpress_plugin_vue_app"]=self["webpackChunkoptinmonster_wordpress_plugin_vue_app"]||[]).push([[841],{80239:function(e,t,n){n.r(t),n.d(t,{default:function(){return u}});var i=function(){var e=this,t=e._self._c;return t("core-page",{attrs:{title:"OptinMonster Onboarding Wizard"}},[t("div",{staticClass:"omapi-onboarding"},[t("div",{staticClass:"omapi-panel"},[t("header",{staticClass:"omapi-panel-header"},[t("img",{attrs:{src:n(37456),alt:"OptinMonster Logo"}})]),e.wizardLoading?t("wizard-screen",{attrs:{completed:!1,"panel-class":"omapi-panel__welcome","content-class":"omapi-text-center","nav-prev":!1,"nav-next":!1}},[t("h2",[e._v("Welcome to the OptinMonster Setup Wizard!")]),t("core-loading")],1):[0===e.step?t("wizard-welcome"):1===e.step?t("wizard-screen1"):2===e.step?t("wizard-screen2"):3===e.step?t("wizard-screen3"):4===e.step?t("wizard-screen4"):5===e.step?t("wizard-screen4-2"):t("wizard-screen5")]],2)])])},r=[],s=n(86080),a=(n(26699),n(20629)),o={name:"OnboardingWizard",computed:(0,s.Z)((0,s.Z)((0,s.Z)({},(0,a.rn)("wizard",["step"])),(0,a.rn)(["apiKey"])),{},{wizardLoading:function(){return this.apiKey&&this.$store.getters.isLoading("me")&&4>this.step}}),watch:{step:function(){this.$store.dispatch("wizard/maybeGoBack")}},mounted:function(){window.addEventListener("keydown",this.maybeNav),this.$bus.$on("onboardingNextPage",this.nextScreen),this.$bus.$on("onboardingPrevPage",this.prevScreen)},beforeDestroy:function(){window.removeEventListener("keydown",this.maybeNav),this.$bus.$off("onboardingNextPage",this.nextScreen),this.$bus.$off("onboardingPrevPage",this.prevScreen)},methods:{maybeNav:function(e){e.metaKey||["INPUT","TEXTAREA","SELECT"].includes(document.activeElement.nodeName)||("ArrowRight"===e.code&&this.$bus.$emit("onboardingNextPage"),"ArrowLeft"===e.code&&this.$bus.$emit("onboardingPrevPage"))},nextScreen:function(){var e=this.step+1,t={canProceed:7>e,current:this.step,next:e};this.$bus.$emit("onboardingCanNextPage",t),t.canProceed&&this.$store.commit("wizard/setStep",e)},prevScreen:function(){var e=this.step-1,t={canProceed:0<=e,current:this.step,prev:e};this.$bus.$emit("onboardingCanPrevPage",t),t.canProceed&&this.$store.commit("wizard/setStep",e)}}},c=o,d=n(1001),p=(0,d.Z)(c,i,r,!1,null,null,null),u=p.exports},37456:function(e,t,n){e.exports=n.p+"img/logo-om.329fe4bf.png"}}]);
//# sourceMappingURL=onboarding-wizard-legacy.c7eedcce.js.map