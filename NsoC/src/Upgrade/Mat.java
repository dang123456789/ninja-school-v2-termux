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
import server.Service;
import template.ItemTemplate;

/**
 *
 * @author Administrator
 */
public class Mat {

    public static int[] coinUpMat = new int[]{100000, 200000, 300000, 500000, 1000000, 2000000, 3000000, 5000000, 7000000, 10000000}; // xu nâng mắt
    public static int[] goldUpMat = new int[]{1000, 2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000}; // lượng nâng mắt]
    public static int[] percentUpMat = new int[]{100, 30, 25, 20, 15, 10, 8, 5, 3, 1}; // tỉ lệ

    public static void UpgradeMat(Player p, Item item, int type) throws IOException {
        if (item == null) {
            return;
        }
        if ((p.c.yen + p.c.xu) < Mat.coinUpMat[item.upgrade]) {
            Service.chatNPC(p, (short) 2, "Bạn không đủ yên và xu để nâng cấp mắt");
            return;
        }
        if (type == 1 && p.luong < Mat.goldUpMat[item.upgrade]) {
            Service.chatNPC(p, (short) 2, "Bạn không đủ lượng để nâng cấp mắt");
            return;
        }
        Mat.handleUpgradeMat(p, item, type);
        Message m = new Message(13);
        m.writer().writeInt(p.c.xu);//xu
        m.writer().writeInt(p.c.yen);//yen
        m.writer().writeInt(p.luong);//luong
        m.writer().flush();
        p.conn.sendMessage(m);
        m.cleanup();
    }

    private static void handleUpgradeMat(Player p, Item item, int type) {
        try {
            int upPer = Mat.percentUpMat[item.upgrade];
            if (type == 1) {
                upPer *= 2;
            }
            if (Util.nextInt(100) < upPer) {
                p.c.removeItemBody((byte) 14);
                Item itemup = ItemTemplate.itemDefault(685 + item.upgrade, true);
                itemup.quantity = 1;
                itemup.upgrade = (byte) (item.upgrade + 1);
                itemup.isLock = true;
                itemup.options.add(new Option(6, 1000 * itemup.upgrade));
                itemup.options.add(new Option(87, 1000 * itemup.upgrade));
                itemup.options.add(new Option(80, 50 * itemup.upgrade));
                itemup.options.add(new Option(94, 10 * itemup.upgrade));
                itemup.options.add(new Option(100, 5 * itemup.upgrade));
                if (itemup.upgrade >= 3) {
                    itemup.options.add(new Option(79, 25));
                }
                if (itemup.upgrade >= 6) {
                    itemup.options.add(new Option(64, 0));
                }
                if (itemup.upgrade == 10) {
                    itemup.options.add(new Option(113, 5000));
                }
                p.c.addItemBag(false, itemup);
            } else {
                Service.chatNPC(p, (short) 2, "Nâng Cấp Thất Bại !");
            }
            if (p.c.yen < Mat.coinUpMat[item.upgrade]) {
                p.c.xu -= (Mat.coinUpMat[item.upgrade] - p.c.yen);
                p.c.yen = 0;
            } else {
                p.c.yen -= Mat.coinUpMat[item.upgrade];
            }
            if (type == 1) {
                p.luong -= Mat.goldUpMat[item.upgrade];
            }
            p.c.removeItemBags(694 + item.upgrade, 10);

        } catch (Exception e) {
        }
    }
}
