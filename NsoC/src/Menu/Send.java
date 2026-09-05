/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Menu;

import assembly.Char;
import assembly.Player;
import stream.Client;

/**
 *
 * @author Administrator
 */
public class Send {

    public static void sendLuong(Player p) {
        try {
            Char userGF = Client.gI().getNinja(p.nameUS);
            int luongGFF = Integer.parseInt(p.luongGF);
            userGF.p.upluongMessage(luongGFF);
            userGF.p.conn.sendMessageLog("Bạn Nhận Được " + luongGFF + " Lượng Từ Admin");
        } catch (NumberFormatException e) {
            p.sendAddchatYellow("Hãy nhập đúng định dạng");
        }
    }

    public static void sendXu(Player p) {
        try {
            Char userGF = Client.gI().getNinja(p.nameUS);
            int xuGFF = Integer.parseInt(p.xuGF);
            userGF.upxuMessage(xuGFF);
            userGF.p.conn.sendMessageLog("Bạn Nhận Được " + xuGFF + " Xu Từ Admin");
        } catch (NumberFormatException e) {
            p.sendAddchatYellow("Hãy nhập đúng định dạng");
        }

    }
}
