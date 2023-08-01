export class Navbar {
  constructor(navbarElement) {
    this.state = false;
    console.log(this.state);
    document.getElementById("navbar").style.transform = "translate(0%)";
    this.navbarElement = document.getElementById("navbar");
  }

  toggle() {
    if (this.state === false) {
      document.getElementById("navbar").style.transform =
        "translate(100%, 100%)";
    } else {
      document.getElementById("navbar").style.transform = "translate(0)";
    }
    console.log(this.state);
    this.state = !this.state;
  }
}
