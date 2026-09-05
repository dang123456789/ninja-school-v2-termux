/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Upgrade;

import assembly.Item;
import assembly.Option;
import assembly.Player;
import io.Message;
import io.Util;
import java.io.IOException;
import server.GameSrc;
import template.ItemTemplate;

/**
 *
 * @author Administrator
 */
public class UpgradeAngel {

    public static void UpgradeAngel(Player p, Item item, int type) throws IOException {
        if (p.c.quantityItemyTotal(850) < 10 * item.upgrade) {
            ItemTemplate data = ItemTemplate.ItemTemplateId(850);
            p.conn.sendMessageLog("Bạn không đủ " + 10 * item.upgrade + " viên " + data.name + " để nâng cấp");
            return;
        }
        if (type == 1 && p.luong < GameSrc.luongup[item.upgrade]) {
            p.conn.sendMessageLog("Bạn không đủ lượng để nâng cấp");
            return;
        }

        UpgradeAngel.UpgradeAngelOptions(p, item, type);
        Message m = new Message(13);
        m.writer().writeInt(p.c.xu);//xu
        m.writer().writeInt(p.c.yen);//yen
        m.writer().writeInt(p.luong);//luong
        m.writer().flush();
        p.conn.sendMessage(m);
        m.cleanup();
    }

    private static void UpgradeAngelOptions(Player p, Item item, int type) {
        try {
            int upPer = GameSrc.tileup[item.upgrade];
            if (Util.nextInt(150) < upPer) {
                p.c.removeItemBody((byte) 27);
                Item itemup = ItemTemplate.itemDefault(816, true);
                itemup.quantity = 1;
                itemup.upgrade = (byte) (item.upgrade + 1);
                itemup.isLock = true;
                Option op = new Option(58, 10);
                itemup.options.add(op);
                op = new Option(58, 1 * itemup.upgrade);
                itemup.options.add(op);
                op = new Option(120, (int) (62.5 * itemup.upgrade));
                itemup.options.add(op);
                op = new Option(121, 2 * itemup.upgrade);
                itemup.options.add(op);
                op = new Option(57, 10 * itemup.upgrade);
                itemup.options.add(op);
                if (itemup.upgrade >= 8) {
                    op = new Option(84, 10 * itemup.upgrade);
                    itemup.options.add(op);
                }
                if (itemup.upgrade >= 12) {
                    op = new Option(94, 10 * itemup.upgrade);
                    itemup.options.add(op);
                }
                if (itemup.upgrade == 14) {
                    op = new Option(92, 10 * itemup.upgrade);
                    itemup.options.add(op);
                }
                p.c.addItemBag(false, itemup);
            } else {
                p.sendAddchatYellow("Nâng cấp thất bại!");
            }
            if (type == 1) {
                p.luong -= GameSrc.luongup[item.upgrade];
            }
            p.c.removeItemBags(850, 10 * item.upgrade);
        } catch (Exception e) {
        }
    }
}
