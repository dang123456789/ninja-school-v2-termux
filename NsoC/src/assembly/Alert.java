package assembly;

import stream.Server;

/**
 *
 * @author Võ Quang Huy
 */
public class Alert {

    private String alert;

    public Alert() {

    }

    public Alert(String alert) {
        this.alert = alert;
    }

    public String getAlert() {
        return this.alert;
    }

    public void setAlert(String alert) {
        this.alert = alert;
    }

    public void sendAlert(Player player) {
        Server.manager.sendTB(player, " Thông Báo ", (this.getAlert() == null ? "" : this.getAlert()));
    }
}
