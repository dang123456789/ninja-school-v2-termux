package Upgrade;

import History.LichSu;
import assembly.Option;
import assembly.Player;
import io.Util;
import assembly.Item;
import assembly.Language;
import java.io.IOException;
import server.Service;

/**
 *
 * @💖 𝗮𝘂𝘁𝗵𝗼𝗿 𝗻𝗲𝘄𝗯𝗶𝗲 𝗚𝗶𝗮𝗻𝗴💖
 */
public class KhaiHoa {
    public static void ThangNguong(Player p,int index){
        Item item = p.c.getIndexTN(index);
        if(item != null){
            int nguongkhaihoa = 0;
            int tl = -1;
            for (Option option : item.options) {
                if (option.id == 134) {
                    nguongkhaihoa = option.param;
                    break;
                }
            }
            if (nguongkhaihoa >= 9) {
                p.conn.sendMessageLog("Ngưỡng Khai Hoá đã đạt tối đa !");
                return;
            }
            int[] xu = new int[]{1000000, 1500000, 2000000, 3000000, 4000000, 5000000, 7000000, 10000000, 15000000};
            int[] luong = new int[]{1000, 2000, 3000, 4000, 5000, 6000, 7000, 8000, 10000};
            if (p.c.xu < xu[nguongkhaihoa]) {
                p.conn.sendMessageLog("Bạn không đủ xu!");
                return;
            }
            if(p.luong < luong[nguongkhaihoa]){
                p.conn.sendMessageLog("Bạn không đủ lượng!");
                return;
            }
            p.upluong(-luong[nguongkhaihoa]);
            p.c.upxu(-xu[nguongkhaihoa]);
            LichSu.LichSuLuong(p.c.name, p.luong, (p.luong -luong[nguongkhaihoa]), "Ngưỡng Khai Hóa", -luong[nguongkhaihoa]);
            LichSu.LichSuXu(p.c.name, p.c.xu, (p.c.xu - xu[nguongkhaihoa]), "Ngưỡng Khai Hóa", -xu[nguongkhaihoa]);
            int[] tile = new int[]{70, 50, 40, 30, 25, 20, 10, 7, 3};
            if(Util.nextInt(1,100)>tile[nguongkhaihoa]){
                p.conn.sendMessageLog("Thăng Ngưỡng Thất Bại!");
            }else {
                boolean param134 = false;
                for (Option option : item.options) {
                    if (option.id == 134) {
                        option.param += 1;
                        param134 = true;
                        break;
                    }
                }
                if (!param134) {
                    item.options.add(new Option(134, 1));
                    item.options.add(new Option(135, 0));
                }
                p.sendAddchatYellow("Thăng Ngưỡng Thành Công!");
                Service.CharViewInfo(p, false);
                p.endLoad(true);
                p.c.indexTN = -1;
            }
        }
    }
    public static void ThangCap_ST(Player p,int index){
        Item item = p.c.getIndexTN(index);
        if(item != null){
            int capthangnguong = 0;
            for (Option option : item.options) {
                if (option.id == 135) {
                    int capkhaihoa = option.param;
                    int indexST = p.c.getIndexItemByIdInBag(959); // dá khai hóa
                    int[] xu = new int[]{1000000, 1500000, 2000000, 3000000, 4000000, 5000000, 7000000, 10000000, 20000000};
                    int[] luong = new int[]{500, 1000, 1500, 2000, 3000, 4000, 5000, 7000, 10000};
                    int[] da = new int[]{5, 10, 20, 30, 40, 50, 60, 70, 80};
                    if (p.c.xu < xu[capkhaihoa]) {
                        p.conn.sendMessageLog("Bạn không đủ xu!");
                        return;
                    }
                    if(p.luong < luong[capkhaihoa]){
                        p.conn.sendMessageLog("Bạn không đủ lượng!");
                        return;
                    }
                    if (indexST == -1 || p.c.ItemBag[indexST] == null || p.c.ItemBag[indexST].quantity < da[capkhaihoa]) {
                        p.conn.sendMessageLog("Bạn không đủ đá Sát Thương!");
                        return;
                    }
                    p.c.removeItemBag((byte) indexST, da[capkhaihoa]);
                    p.upluong(-luong[capkhaihoa]);
                    p.c.upxu(-xu[capkhaihoa]);
                    LichSu.LichSuLuong(p.c.name, p.luong, (p.luong -luong[capkhaihoa]), "Khai Hóa", -luong[capkhaihoa]);
                    LichSu.LichSuXu(p.c.name, p.c.xu, (p.c.xu - xu[capkhaihoa]), "Khai Hóa", -xu[capkhaihoa]);
                    int[] tile = new int[]{70, 50, 40, 30, 25, 20, 10, 5, 2};
                    if (Util.nextInt(1, 100) > tile[capthangnguong]) {
                        p.conn.sendMessageLog("Thăng Cấp Khai Hoá Thất Bại");
                    } else {
                        option.param++;
                        boolean flag = false;
                        for (Option option1 : item.options) {
                            if (option1.id == 150) {
                                flag = true;
                                break;
                            }
                        }
                        if (!flag) { //sát thương
                            item.options.add(new Option(150, 0));
                            item.options.add(new Option(144, 100));
                            item.options.add(new Option(146, 700));
                            item.options.add(new Option(145, 10));
                            item.options.add(new Option(147, 10));
                        }
                        Service.CharViewInfo(p, false);
                        p.endLoad(true);
                        p.c.indexTN = -1;
                        p.sendAddchatYellow("Thăng Cấp Khai Hoá Thành công");
                    }
                    break;
                }
            }
        }
    }
    public static void ThangCap_PN(Player p,int index) {
        Item item = p.c.getIndexTN(index);
        if (item != null) {
            int capthangnguong = 0;
            for (Option option : item.options) {
                if (option.id == 135) {
                    int capkhaihoa = option.param;
                    int indexST = p.c.getIndexItemByIdInBag(958); // dá khai hóa
                    int[] xu = new int[]{1000000, 1500000, 2000000, 3000000, 4000000, 5000000, 7000000, 10000000, 20000000};
                    int[] luong = new int[]{500, 1000, 1500, 2000, 3000, 4000, 5000, 7000, 10000};
                    int[] da = new int[]{5, 10, 20, 30, 40, 50, 60, 70, 80};
                    if (p.c.xu < xu[capkhaihoa]) {
                        p.conn.sendMessageLog("Bạn không đủ xu!");
                        return;
                    }
                    if(p.luong < luong[capkhaihoa]){
                        p.conn.sendMessageLog("Bạn không đủ lượng!");
                        return;
                    }
                    if (indexST == -1 || p.c.ItemBag[indexST] == null || p.c.ItemBag[indexST].quantity < da[capkhaihoa]) {
                        p.conn.sendMessageLog("Bạn không đủ đá Phòng Ngự!!");
                        return;
                    }
                    p.c.removeItemBag((byte) indexST, da[capkhaihoa]);
                    p.upluong(-luong[capkhaihoa]);
                    p.c.upxu(-xu[capkhaihoa]);
                    LichSu.LichSuLuong(p.c.name, p.luong, (p.luong -luong[capkhaihoa]), "Khai Hóa", -luong[capkhaihoa]);
                    LichSu.LichSuXu(p.c.name, p.c.xu, (p.c.xu - xu[capkhaihoa]), "Khai Hóa", -xu[capkhaihoa]);
                    int[] tile = new int[]{70, 50, 40, 30, 25, 20, 10, 5, 2};
                    if (Util.nextInt(1, 100) > tile[capthangnguong]) {
                        p.conn.sendMessageLog("Thăng Cấp Khai Hoá Thất Bại");
                    } else {
                        option.param++;
                        boolean flag = false;
                        for (Option option1 : item.options) {
                            if (option1.id == 149) {
                                flag = true;
                                break;
                            }
                        }
                        if (!flag) { //sát thương
                            item.options.add(new Option(149, 0));
                            item.options.add(new Option(140, 130));
                            item.options.add(new Option(141, 50));
                            item.options.add(new Option(142, 50));
                            item.options.add(new Option(143, 50));
                        }
                        Service.CharViewInfo(p, false);
                        p.endLoad(true);
                        p.c.indexTN = -1;
                        p.sendAddchatYellow("Thăng Cấp Khai Hoá Thành công");
                    }
                    break;
                }
            }
        }
    }
    public static void ThangCap_SM(Player p,int index) {
        Item item = p.c.getIndexTN(index);
        if (item != null) {
            int capthangnguong = 0;
            for (Option option : item.options) {
                if (option.id == 135) {
                    int capkhaihoa = option.param;
                    int indexST = p.c.getIndexItemByIdInBag(957); // dá khai hóa
                    int[] xu = new int[]{1000000, 1500000, 2000000, 3000000, 4000000, 5000000, 7000000, 10000000, 20000000};
                    int[] luong = new int[]{500, 1000, 1500, 2000, 3000, 4000, 5000, 7000, 10000};
                    int[] da = new int[]{5, 10, 20, 30, 40, 50, 60, 70, 80};
                    if (p.c.xu < xu[capkhaihoa]) {
                        p.conn.sendMessageLog("Bạn không đủ xu!");
                        return;
                    }
                    if(p.luong < luong[capkhaihoa]){
                        p.conn.sendMessageLog("Bạn không đủ lượng!");
                        return;
                    }
                    if (indexST == -1 || p.c.ItemBag[indexST] == null || p.c.ItemBag[indexST].quantity < da[capkhaihoa]) {
                        p.conn.sendMessageLog("Bạn không đủ đá Sinh Mệnh!");
                        return;
                    }
                    p.c.removeItemBag((byte) indexST, da[capkhaihoa]);
                    p.upluong(-luong[capkhaihoa]);
                    p.c.upxu(-xu[capkhaihoa]);
                    LichSu.LichSuLuong(p.c.name, p.luong, (p.luong -luong[capkhaihoa]), "Khai Hóa", -luong[capkhaihoa]);
                    LichSu.LichSuXu(p.c.name, p.c.xu, (p.c.xu - xu[capkhaihoa]), "Khai Hóa", -xu[capkhaihoa]);
                    int[] tile = new int[]{70, 50, 40, 30, 25, 20, 10, 5, 2};
                    if (Util.nextInt(1, 100) > tile[capthangnguong]) {
                        p.conn.sendMessageLog("Thăng Cấp Khai Hoá Thất Bại");
                    } else {
                        option.param++;
                        boolean flag = false;
                        for (Option option1 : item.options) {
                            if (option1.id == 148) {
                                flag = true;
                                break;
                            }
                        }
                        if (!flag) { //sát thương
                            item.options.add(new Option(148, 0));
                            item.options.add(new Option(136, 200));
                            item.options.add(new Option(137, 200));
                            item.options.add(new Option(138, 5));
                            item.options.add(new Option(139, 10));
                        }
                        Service.CharViewInfo(p, false);
                        p.endLoad(true);
                        p.c.indexTN = -1;
                        p.sendAddchatYellow("Thăng Cấp Khai Hoá Thành công");
                    }
                    break;
                }
            }
        }
    }
    public static void ThangNguong170(Player p,int index){
        Item item = p.c.getIndexTN(index);
        if (item != null) {
            for (Option option : item.options) {
                switch (option.id) {
                    case 135:
                        int capkhaihoa = option.param;
                        int indexSM = p.c.getIndexItemByIdInBag(958); // dá khai hóa
                        int[] xu = new int[]{1000000, 1500000, 2000000, 3000000, 4000000, 5000000, 7000000, 10000000, 20000000};
                        int[] luong = new int[]{500, 1000, 1500, 2000, 3000, 4000, 5000, 7000, 10000};
                        int[] da = new int[]{5, 10, 20, 30, 40, 50, 60, 70, 80};
                        if (p.c.xu < xu[capkhaihoa]) {
                            p.conn.sendMessageLog("Bạn không đủ xu!");
                            return;
                        }
                        if(p.luong < luong[capkhaihoa]){
                            p.conn.sendMessageLog("Bạn không đủ lượng!");
                            return;
                        }
                        if (indexSM == -1 || p.c.ItemBag[indexSM] == null || p.c.ItemBag[indexSM].quantity < da[capkhaihoa]) {
                            p.conn.sendMessageLog("Bạn không đủ đá Sinh Mệnh!");
                            return;
                        }
                        p.c.removeItemBag((byte) indexSM, da[capkhaihoa]);
                        p.upluong(-luong[capkhaihoa]);
                        p.c.upxu(-xu[capkhaihoa]);
                        LichSu.LichSuLuong(p.c.name, p.luong, (p.luong -luong[capkhaihoa]), "Khai Hóa", -luong[capkhaihoa]);
                        LichSu.LichSuXu(p.c.name, p.c.xu, (p.c.xu - xu[capkhaihoa]), "Khai Hóa", -xu[capkhaihoa]);
                        int[] tile = new int[]{70, 50, 40, 30, 25, 20, 10, 5, 2};
                        if (Util.nextInt(1, 100) > tile[capkhaihoa]) {
                            p.sendAddchatYellow("Thăng Cấp Khai Hoá Thất Bại");
                        } else {
                            option.param++;
                            for (Option option1 : item.options) {
                                switch (option1.id) {
                                    case 136: 
                                    case 137:
                                    case 146:
                                        option1.param += 100;
                                        break;
                                    case 138:
                                        option1.param += 1;
                                        break;
                                    case 139:
                                    case 140:
                                    case 141:
                                    case 142:
                                    case 143:
                                    case 144:
                                        option1.param += 20;
                                        break;
                                    case 147:
                                        option1.param += 2;
                                        break;
                                    case 145:
                                        option1.param += 5;
                                        break;
                                }
                            }
                            Service.CharViewInfo(p, false);
                            p.endLoad(true);
                            p.c.indexTN = -1;
                            p.sendAddchatYellow("Thăng Cấp Khai Hoá Thành công");
                        }
                }
            }
        }
    }
    public static void ThangNguong171(Player p,int index){
        Item item = p.c.getIndexTN(index);
        if (item != null) {
            for (Option option : item.options) {
                switch (option.id) {
                    case 135:
                        int capkhaihoa = option.param;
                        int indexSM = p.c.getIndexItemByIdInBag(959); // dá khai hóa
                        int[] xu = new int[]{1000000, 1500000, 2000000, 3000000, 4000000, 5000000, 7000000, 10000000, 20000000};
                        int[] luong = new int[]{500, 1000, 1500, 2000, 3000, 4000, 5000, 7000, 10000};
                        int[] da = new int[]{5, 10, 20, 30, 40, 50, 60, 70, 80};
                        if (p.c.xu < xu[capkhaihoa]) {
                            p.conn.sendMessageLog("Bạn không đủ xu!");
                            return;
                        }
                        if(p.luong < luong[capkhaihoa]){
                            p.conn.sendMessageLog("Bạn không đủ lượng!");
                            return;
                        }
                        if (indexSM == -1 || p.c.ItemBag[indexSM] == null || p.c.ItemBag[indexSM].quantity < da[capkhaihoa]) {
                            p.conn.sendMessageLog("Bạn không đủ đá Phòng Ngự!!");
                            return;
                        }
                        p.c.removeItemBag((byte) indexSM, da[capkhaihoa]);
                        p.upluong(-luong[capkhaihoa]);
                        p.c.upxu(-xu[capkhaihoa]);
                        LichSu.LichSuLuong(p.c.name, p.luong, (p.luong -luong[capkhaihoa]), "Khai Hóa", -luong[capkhaihoa]);
                        LichSu.LichSuXu(p.c.name, p.c.xu, (p.c.xu - xu[capkhaihoa]), "Khai Hóa", -xu[capkhaihoa]);
                        int[] tile = new int[]{70, 50, 40, 30, 25, 20, 10, 5, 2};
                        if (Util.nextInt(1, 100) > tile[capkhaihoa]) {
                            p.sendAddchatYellow("Thăng Cấp Khai Hoá Thất Bại");
                        } else {
                            option.param++;
                            for (Option option1 : item.options) {
                                switch (option1.id) {
                                    case 136:
                                    case 137:
                                    case 146:
                                        option1.param += 100;
                                        break;
                                    case 138:
                                        option1.param += 1;
                                        break;
                                    case 139:
                                    case 140:
                                    case 141:
                                    case 142:
                                    case 143:
                                    case 144:
                                        option1.param += 20;
                                        break;
                                    case 147:
                                        option1.param += 2;
                                        break;
                                    case 145:
                                        option1.param += 5;
                                        break;
                                }
                            }
                            Service.CharViewInfo(p, false);
                            p.endLoad(true);
                            p.c.indexTN = -1;
                            p.sendAddchatYellow("Thăng Cấp Khai Hoá Thành công");
                        }
                }
            }
        }
    }
    public static void  ThangNguong172(Player p,int index){
        Item item = p.c.getIndexTN(index);
        if (item != null) {
            for (Option option : item.options) {
                switch (option.id) {
                    case 135:
                        int capkhaihoa = option.param;
                        int indexSM = p.c.getIndexItemByIdInBag(959); // dá khai hóa
                        int[] xu = new int[]{1000000, 1500000, 2000000, 3000000, 4000000, 5000000, 7000000, 10000000, 20000000}; 
                        int[] luong = new int[]{500, 1000, 1500, 2000, 3000, 4000, 5000, 7000, 10000}; 
                        int[] da = new int[]{5, 10, 20, 30, 40, 50, 60, 70, 80}; 
                        if (p.c.xu < xu[capkhaihoa]) {
                            p.conn.sendMessageLog("Bạn không đủ xu!");
                            return;
                        }
                        if(p.luong < luong[capkhaihoa]){
                            p.conn.sendMessageLog("Bạn không đủ lượng!");
                            return;
                        }
                        if (indexSM == -1 || p.c.ItemBag[indexSM] == null || p.c.ItemBag[indexSM].quantity < da[capkhaihoa]) {
                            p.conn.sendMessageLog("Bạn không đủ đá Sát Thương!");
                            return;
                        }
                        p.c.removeItemBag((byte) indexSM, da[capkhaihoa]);
                        p.upluong(-luong[capkhaihoa]);
                        p.c.upxu(-xu[capkhaihoa]);
                        LichSu.LichSuLuong(p.c.name, p.luong, (p.luong -luong[capkhaihoa]), "Khai Hóa", -luong[capkhaihoa]);
                        LichSu.LichSuXu(p.c.name, p.c.xu, (p.c.xu - xu[capkhaihoa]), "Khai Hóa", -xu[capkhaihoa]);
                        int[] tile = new int[]{70, 50, 40, 30, 25, 20, 10, 5, 2};
                        if (Util.nextInt(1, 100) > tile[capkhaihoa]) {
                            p.sendAddchatYellow("Thăng Cấp Khai Hoá Thất Bại");
                        } else {
                            option.param++; 
                            for (Option option1 : item.options) { 
                                switch (option1.id) {
                                    case 136:
                                    case 137:
                                    case 146:
                                        option1.param += 100; 
                                        break;
                                    case 138:
                                        option1.param += 1;
                                        break;
                                    case 139:
                                    case 140:
                                    case 141:
                                    case 142:
                                    case 143:
                                    case 144:
                                        option1.param += 20;
                                        break;
                                    case 147:
                                        option1.param += 2;
                                        break;
                                    case 145:
                                        option1.param += 5;
                                        break;
                                }
                            }
                            Service.CharViewInfo(p, false);
                            p.endLoad(true);
                            p.c.indexTN = -1;
                            p.sendAddchatYellow("Thăng Cấp Khai Hoá Thành công");
                        }
                }
            }
        }
    }
}
