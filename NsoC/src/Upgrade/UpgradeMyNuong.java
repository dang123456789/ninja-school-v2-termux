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
public class UpgradeMyNuong {

    public static void UpgradeMyNuong(Player p, Item item, int type) throws IOException {
        if (item.upgrade >= 16) {
            p.conn.sendMessageLog("đã nâng cấp tối đa");
            return;
        }
        if (p.c.quantityItemyTotal(659) < 10 * item.upgrade) {
            ItemTemplate data = ItemTemplate.ItemTemplateId(659);
            p.conn.sendMessageLog("Bạn không đủ " + 10 * item.upgrade + " viên " + data.name + " để nâng cấp");
            return;
        }
        if (type == 1 && p.luong < GameSrc.luongup[item.upgrade]) {
            p.conn.sendMessageLog("Bạn không đủ lượng để nâng cấp");
            return;
        }

        UpgradeMyNuong.UpgradeMyNuongOptions(p, item, type);
        Message m = new Message(13);
        m.writer().writeInt(p.c.xu);//xu
        m.writer().writeInt(p.c.yen);//yen
        m.writer().writeInt(p.luong);//luong
        m.writer().flush();
        p.conn.sendMessage(m);
        m.cleanup();
    }

    public static void UpgradeMyNuongOptions(Player p, Item item, int type) {
        try {
            int upPer = GameSrc.tileup[item.upgrade];
            if (Util.nextInt(150) < upPer) {
                p.c.removeItemBody((byte) 11);
                Item itemup = ItemTemplate.itemDefault(833, true);
                itemup.quantity = 1;
                itemup.upgrade = (byte) (item.upgrade + 1);
                itemup.isLock = true;
                Option op = new Option(58, 5);
                itemup.options.add(op);
                op = new Option(58, 1 * itemup.upgrade);
                itemup.options.add(op);
                op = new Option(6, 500 * itemup.upgrade);
                itemup.options.add(op);
                op = new Option(73, 500 * itemup.upgrade);
                itemup.options.add(op);
                if (itemup.upgrade >= 8) {
                    op = new Option(84, 10 * itemup.upgrade);
                    itemup.options.add(op);
                }
                if (itemup.upgrade >= 12) {
                    op = new Option(86, 10 * itemup.upgrade);
                    itemup.options.add(op);
                }
                if (itemup.upgrade == 14) {
                    op = new Option(98, 2 * itemup.upgrade);
                    itemup.options.add(op);
                }
                if (itemup.upgrade == 16) {
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
            p.c.removeItemBags(659, 10 * item.upgrade);
        } catch (Exception e) {
        }
    }
}
