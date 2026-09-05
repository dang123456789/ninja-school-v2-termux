package assembly;

import Item.ItemHandleUseEvent;
import Item.ItemName;
import Item.ItemHandleUse;
import Item.RandomItem;
import io.Message;
import io.Util;
import server.GameSrc;
import server.Manager;
import server.Service;
import stream.BossTuanLoc;
import stream.Server;
import template.ItemTemplate;

import java.io.IOException;

public class UseItem {

    static int[] arrOp = new int[]{6, 7, 10, 67, 68, 69, 70, 71, 72, 73, 74};
    static int[] arrParam = new int[]{50, 50, 10, 5, 10, 10, 5, 5, 5, 100, 50};
    private static final byte[] arrOpenBag = new byte[]{0, 6, 6, 12, 72};
    private static final Object LOCK = new Object();
    public static short[] HanSuDung = new short[]{3, 7};
    public static short[] HanSuDungNew = new short[]{7, 15};
    public static short[] idItemRuongMayMan = new short[]{4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 5, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 6, 6, 7, 7, 7, 7, 242, 280, 436};
    public static short[] idItemRuongTinhXao = new short[]{4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 6, 6, 6, 7, 4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 6, 6, 6, 7, 7, 436, 437, 242, 280, 283, 436, 437, 437, 269};
    public static short[] idItemRuongMaQuai = new short[]{4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7, 7, 4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7, 7, 280, 280, 280, 436, 437, 436, 437, 618, 619, 620, 621, 622, 623, 624, 625, 626, 627, 628, 629, 630, 631, 540, 539, 632, 633, 634, 635, 636, 637, 223, 224, 225, 226, 227, 228};
    public static short[] idItemHopBanhThuong = new short[]{4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7, 7, 7, 8, 8, 275, 276, 277, 278, 289, 340, 340, 383, 409, 410, 419, 436, 436, 454, 454, 457, 436, 436, 436, 437, 437, 443, 485, 524, 549, 550, 551, 568, 569, 570, 571, 577, 742};
    public static short[] idItemHopBanhThuongHang = new short[]{4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7, 8, 8, 8, 9, 9, 10, 10, 11, 11, 275, 276, 277, 278, 289, 340, 340, 384, 409, 410, 419, 436, 436, 436, 436, 436, 436, 437, 437, 438, 443, 454, 454, 457, 457, 485, 524, 539, 567, 567, 549, 550, 551, 568, 569, 570, 571, 308, 309, 577, 742, 781};
    public static short[] idItemDieuGiay = new short[]{4, 4, 4, 4, 5, 5, 5, 5, 6, 6, 6, 7, 7, 7, 7, 7, 8, 9, 275, 276, 277, 278, 289, 289, 340, 340, 383, 409, 410, 436, 436, 436, 436, 436, 437, 437, 443, 485, 524, 549, 550, 551, 569, 577, 742};
    public static short[] idItemDieuVai = new short[]{4, 4, 5, 5, 6, 6, 7, 7, 8, 9, 10, 11, 275, 276, 277, 278, 289, 340, 340, 383, 409, 410, 419, 436, 436, 436, 436, 436, 436, 437, 437, 438, 443, 485, 524, 567, 567, 549, 550, 551, 569, 577, 742, 781};
    public static short[] idItemPhucNangNhanGia = new short[]{-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, -1};
    public static short[] idItemBanhChocolate = new short[]{4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 6, 6, 6, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 8, 9, 275, 276, 277, 278, 289, 289, 340, 340, 383, 409, 410, 436, 436, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 7, 7, 7, 437, 443, 485, 524, 549, 550, 551, 549, 550, 551, 569, 574, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 577, 575, 578, 742, 673, 775};
    public static short[] idItemBanhDauTay = new short[]{4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7, 7, 7, 7, 7, 8, 9, 10, 275, 276, 277, 278, 289, 340, 340, 383, 409, 410, 419, 436, 436, 436, 5, 5, 5, 5, 6, 6, 6, 6, 7, 7, 7, 436, 437, 437, 438, 443, 485, 524, 567, 567, 549, 550, 551, 549, 5, 5, 5, 5, 5, 6, 6, 6, 7, 7, 7, 7, 550, 551, 775, 569, 575, 5, 5, 5, 6, 6, 6, 6, 6, 7, 7, 578, 574, 577, 742, 673, 775, 781};
    public static short[] idItemCayThong = new short[]{8, 8, 8, 8, 8, 8, 8, 9, 9, 9, 549, 549, 549, 549, 550, 550, 551, 551, 436, 436, 437};
    public static short[] RUONG_SACH_VO_CONG_10X = new short[]{558 , 559 , 560 , 561 , 562 , 563};
//    public static short[] RUONG_DANH_HIEU = new short[]{915 , 916 , 932 , 917 , 918 , 919, 920, 921, 922, 923, 924, 925, 926, 927, 928, 929, 930, 921};
    public static short[] svc12x = new short[]{941, 942, 943, 944, 945, 946};  
    public static short[] vk10x = new short[]{881, 882, 883, 884, 885, 886};
    public static short[] vk12x = new short[]{909, 910, 911, 912, 913, 914};
    public static short[] tb125 = new short[]{1025, 1026, 1027, 1028, 1029, 1030, 1031, 1032, 1033, 1034, 1035, 1036, 1037, 1038, 1039, 1040, 1041, 1042};
    public static short[] dh = new short[]{915, 916, 917, 918, 919, 920, 921, 922, 923, 924, 925, 926, 927, 928, 929, 930, 931, 932, 935, 940, 950, 954, 974, 975, 976, 977, 987, 979, 987, 988, 989};
    public static short[] tb12x = new short[]{895, 896, 897, 898, 899, 900, 901, 902, 903, 904, 905, 906, 907, 908};
    public static short[] tb10x = new short[]{867, 868, 869, 870, 871, 872, 873, 874, 875, 876, 877, 878, 879, 880};
    public static short[] idItemTuiQuaGiaToc = new short[]{8, 8, 8, 8, 8, 8, 8, 7, 7, 7, 7, 7, 7, 7, 7, 8, 8, 8, 8, 8, 8, 8, 8, 242, 242, 242, 283, 436, 436, 437};
    public static short[] idItemHomBlackFriday = new short[]{4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 6, 6, 6, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 8, 9, 275, 276, 277, 278, 289, 289, 340, 340, 383, 409, 410, 436, 436, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 7, 7, 7, 437, 443, 485, 524, 549, 550, 551, 549, 550, 551, 569, 574, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 577, 575, 578, 742, 673, 775, 828};
    public static short[] idItemBanhChung = new short[]{
        5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7, // đá 8 là max
        275, 276, 277, 278, // đan
        409, 410, // thức ăn 6x 7x
        419, // chim tinh anh
        436, 436, 436, 436, 436, 436, 437, 437, 438, // thẻ bài sơ trung cao
        455, 455, 778, 778, 778, 778, 778, 778, 778,
        449, 449, 449, 450, 450, 450, 451, 451, 451, 451,
        539, // x3
        540, // x4
        284, 285,
        573, 576, 573, 576,
        567, 567, // ta 9x
        695, 696, 697,
        652, 653, 654, 655,
        549, 550, 551}; // giày
    public static short[] idItemBanhtet = new short[]{
        5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7,
        5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 6,
        5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 6,
        275, 276, 277, 278, // đan
        778, 778, 778, 778, 778, 778, 778,
        436, 436, 436, 436, 436, 436, 437, 437, 438, // thẻ bài sơ trung cao
        449, 449, 449, 450, 450, 450, 451, 451, 451, 451, // exp sói 2x
        449, 449, 449, 450, 450, 450, 451, 451, 451, 451,
        695, 696};// đá danh vọng
    public static short[] idngoisao = new short[]{
        7, 7, 7, 7, 7, 7, 8, 8, 8, 8, 8, 8, 8, 8, // đá 8 là max
        275, 276, 277, 278, // đan
        409, 410, // thức ăn 6x 7x     
        436, 436, 436, 436, 436, 436, 437, 437, 438, // thẻ bài sơ trung cao
        455, 778, 778, 778, 778, 778, 778, 778,
        449, 449, 449, 450, 450, 450, 451, 451, 451, 451,
        539, // x3
        540, // x4
        284, 285,
        573, 576, 573, 576,
        567, 567, // ta 9x
        695, 696, 697,
        652, 653, 654, 655,
        549, 550, 551}; // giày
    public static short[] iditemrac = new short[]{
        5, 5, 5, 5, 5, 5, 6, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7, 8, 8, 9, // đá 8 là max
        275, 276, 277, 278, // đan
        409, 410, // thức ăn 6x 7x
        436, 437, // thẻ bài sơ trung
        455, // tts
        778, 778, // exp trâu
        284, // bảo hiểm sơ
        567, // ta 9x
        695, 696, 697, // đá danh vọng 1 2 3
        652, 653, 654, 655, // ngọc
        733, 734, 735, 736, 737, 738, 739, 740, 741, // Mảnh Jirai
        760, 761, 762, 763, 764, 765, 766, 767, 768, // Mảnh Jumito
        549, 550, 551}; // giày

    public static void uesItem(Player p, Item item, byte index) {
        Message m = null;
        try {
            long num2 = Level.getLevel(p.c.level).exps;
            boolean checkExpDown = false;
            if (p.c.expdown > num2 * 30L / 100L) {
                checkExpDown = true;
            }
            if (p.c.isNhanban) {
                num2 = Level.getLevel(p.c.clone.level).exps;
                if (p.c.clone.expdown > num2 * 30L / 100L) {
                    checkExpDown = true;
                }
            }
            if (ItemTemplate.ItemTemplateId(item.id).level > p.c.get().level) {
                p.sendAddchatYellow("Trình độ không đủ để sử dụng vật phẩm này.");
                return;
            }
            ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);

            if (data.gender != 2 && data.gender != p.c.gender) {
                return;
            }
            if (data.type == 26) {
                p.sendAddchatYellow("Vật phẩm liên quan đến nâng cấp, hãy gặp Kenshinto trong làng để sử dụng.");
                return;
            }

            if (data.level > p.c.get().level || (p.c.isNhanban && data.level > p.c.clone.level)) {
                p.sendAddchatYellow("Trình độ không đủ để sử dụng vật phẩm này.");
                return;
            }

            if (item.id != 194) {
                if ((data.nclass > 0 && data.nclass != p.c.get().nclass)) {
                    p.sendAddchatYellow("Môn phái không phù hợp");
                    return;
                }
            }
            if ((item.id == 420 && GameSrc.SysClass(p.c.get().nclass) != 1) || (item.id == 421 && GameSrc.SysClass(p.c.get().nclass) != 2) || (item.id == 422 && GameSrc.SysClass(p.c.get().nclass) != 3)) {
                p.sendAddchatYellow("Thuộc tính của Yoroi không phù hợp để sử dụng.");
                return;
            }
            if (p.c.isNhanban && item.id == 547) {
                p.sendAddchatYellow("Chức năng này không thể sử dụng cho phân thân.");
                return;
            }
            if (ItemTemplate.isTypeBody(item.id)) {
                item.isLock = true;
                Item itemb = null;
                if (ItemTemplate.isIdNewCaiTrang(item.id) || ItemTemplate.checkIdNewWP(item.id) != -1 || ItemTemplate.checkIdNewMatNa(item.id) != -1 || ItemTemplate.checkIdNewBienHinh(item.id) != -1) {
                    itemb = p.c.get().ItemBody[data.type + 16];
                    p.c.get().ItemBody[data.type + 16] = item;
                } else {
                    itemb = p.c.get().ItemBody[data.type];
                    p.c.get().ItemBody[data.type] = item;
                }
                p.c.ItemBag[index] = itemb;
                if (data.type == 10) {
                    p.mobMeMessage(0, (byte) 0);
                }
                if (itemb != null && (itemb.id == 569)) {
                    p.removeEffect(36);
                }
                switch (item.id) {
                    case 246: {
                        p.mobMeMessage(70, (byte) 0);
                        break;
                    }
                    case 419: {
                        p.mobMeMessage(122, (byte) 0);
                        break;
                    }
                    case 840: {
                        p.mobMeMessage(97, (byte) 0);
                        break;
                    }
                    case 568: {
                        p.setEffect(38, 0, (int) (item.expires - System.currentTimeMillis()), p.c.get().getPramItem(100));
                        p.mobMeMessage(205, (byte) 0);
                        break;
                    }
                    case 569: {
                        p.setEffect(36, 0, (int) (item.expires - System.currentTimeMillis()), p.c.get().getPramItem(99));
                        p.mobMeMessage(206, (byte) 0);
                        break;
                    }
                    case 570: {
                        p.setEffect(37, 0, (int) (item.expires - System.currentTimeMillis()), p.c.get().getPramItem(98));
                        p.mobMeMessage(207, (byte) 0);
                        break;
                    }
                    case 571: {
                        p.setEffect(39, 0, (int) (item.expires - System.currentTimeMillis()), 0);
                        p.mobMeMessage(208, (byte) 0);
                        break;
                    }
                   
                    case 583: {
                        p.mobMeMessage(211, (byte) 1);
                        break;
                    }
                    case 584: {
                        p.mobMeMessage(212, (byte) 1);
                        break;
                    }
                    case 585: {
                        p.mobMeMessage(213, (byte) 1);
                        break;
                    }
                    case 586: {
                        p.mobMeMessage(214, (byte) 1);
                        break;
                    }
                    case 587: {
                        p.mobMeMessage(215, (byte) 1);
                        break;
                    }
                    case 588: {
                        p.mobMeMessage(216, (byte) 1);
                        break;
                    }
                    case 589: {
                        p.mobMeMessage(217, (byte) 1);
                        break;
                    }
                    case 742: {
                        p.mobMeMessage(229, (byte) 1);
                        break;
                    }
                    case 744: {
                        p.mobMeMessage(229, (byte) 1);
                        break;
                    }
                    case 781: {
                        p.mobMeMessage(235, (byte) 1);
                        break;
                    }
                    case 832: {
                        p.mobMeMessage(238, (byte) 1);
                        break;
                    }
                    // tử hạ ma thần
//                    case 1007: {
//                        //p.setEffect(21, 0, (int) (item.expires - System.currentTimeMillis()), p.c.get().getPramItem(99));
//                        p.mobMeMessage(223, (byte) 1);
//                        break;
//                    }
//                    case 1013: {
//                        p.setEffect(21, 0, (int) (item.expires - System.currentTimeMillis()), p.c.get().getPramItem(97));
//                        p.mobMeMessage(233, (byte) 0);
//                        break;
//                    }
//                    case 1014: {
//                        //p.setEffect(21, 0, (int) (item.expires - System.currentTimeMillis()), p.c.get().getPramItem(99));
//                        p.mobMeMessage(220, (byte) 1);
//                        break;
//                    }
//                    case 1008: {
//                        //p.setEffect(21, 0, (int) (item.expires - System.currentTimeMillis()), p.c.get().getPramItem(99));
//                        p.mobMeMessage(222, (byte) 1);
//                        break;
//                    }
//                    case 1016: {
//                        p.setEffect(21, 0, (int) (item.expires - System.currentTimeMillis()), p.c.get().getPramItem(0));
//                        p.mobMeMessage(233, (byte) 1);
//                        break;
//                    }
                    case 847:
                        p.mobMeMessage(240, (byte) 1);
                        break;
                    case 848:
                        p.mobMeMessage(239, (byte) 1);
                        break;
                    case 849:
                        p.mobMeMessage(241, (byte) 1);
                        break;
                    case 850:
                        p.mobMeMessage(250, (byte) 1);
                        break;
                    case 851:
                        p.mobMeMessage(251, (byte) 1);
                        break;
                    case 852:
                        p.mobMeMessage(243, (byte) 1);
                        break;
                    case 853:
                        p.mobMeMessage(244, (byte) 1);
                        break;
                    case 854:
                        p.mobMeMessage(245, (byte) 1);
                        break;
                    case 855:
                        p.mobMeMessage(246, (byte) 1);
                        break;
                }
                m = new Message(11);
                m.writer().writeByte(index);
                m.writer().writeByte(p.c.get().speed());
                m.writer().writeInt(p.c.get().getMaxHP());
                m.writer().writeInt(p.c.get().getMaxMP());
                m.writer().writeShort(p.c.get().eff5buffHP());
                m.writer().writeShort(p.c.get().eff5buffMP());
                m.writer().flush();
                p.conn.sendMessage(m);
                m.cleanup();
                if ((item.id >= 795 && item.id <= 805) || (item.id >= 813 && item.id <= 817) || (item.id >= 825 && item.id <= 826) || (item.id >= 829 && item.id <= 831 || item.id == 839)) {
                    final Message m1 = new Message(57);
                    m1.writer().flush();
                    p.conn.sendMessage(m1);
                    m1.cleanup();
                    if (!p.c.isTrade) {
                        Service.CharViewInfo(p, false);
                    }
                }
            } else if (ItemTemplate.isTypeMounts(item.id)) {
                byte idM = (byte) (data.type - 29);
                Item itemM = p.c.get().ItemMounts[idM];
                if (idM == 4) {
                    if (p.c.get().ItemMounts[0] != null || p.c.get().ItemMounts[1] != null || p.c.get().ItemMounts[2] != null || p.c.get().ItemMounts[3] != null) {
                        p.conn.sendMessageLog("Bạn cần phải tháo trang bị và thú cưỡi đang sử dụng.");
                        return;
                    }
                    if (!item.isLock) {
                        byte i;
                        int op;
                        Option option2;
                        for (i = 0; i < 4; ++i) {
                            op = -1;
                            do {
                                op = Util.nextInt(UseItem.arrOp.length);
                                for (Option option : item.options) {
                                    if (UseItem.arrOp[op] == option.id) {
                                        op = -1;
                                        break;
                                    }
                                }
                            } while (op == -1);
                            if (op == -1) {
                                return;
                            }
                            int par = UseItem.arrParam[op];
                            if (item.isExpires) {
                                par *= 10;
                            }
                            option2 = new Option(UseItem.arrOp[op], par);
                            item.options.add(option2);
                        }
                    }

                    if (p.c.clone != null && p.c.isNhanban) {
                        if (item.id == 801) {
                            p.c.clone.ID_HORSE = 47;
                        }
                        if (item.id == 802) {
                            p.c.clone.ID_HORSE = 48;
                        }
                        if (item.id == 803) {
                            p.c.clone.ID_HORSE = 49;
                        }
                        if (item.id == 798) {
                            p.c.clone.ID_HORSE = 36;
                        }
                        if (item.id == 828) {
                            p.c.clone.ID_HORSE = 63;
                        }
                        Service.CharViewInfo(p, false);
                    }
                }
//                else if (p.c.get().ItemMounts[4] == null) {
//                    p.conn.sendMessageLog("Bạn cần phải tháo trang bị thú cưỡi đang sử dụng.");
//                    return;
//                }
                item.isLock = true;
                p.c.ItemBag[index] = itemM;
                p.c.get().ItemMounts[idM] = item;
                m = new Message(11);
                m.writer().writeByte(index);
                m.writer().writeByte(p.c.get().speed());
                m.writer().writeInt(p.c.get().getMaxHP());
                m.writer().writeInt(p.c.get().getMaxMP());
                m.writer().writeShort(p.c.get().eff5buffHP());
                m.writer().writeShort(p.c.get().eff5buffMP());
                m.writer().flush();
                p.conn.sendMessage(m);
                m.cleanup();
                if (ItemTemplate.isTypeMounts(item.id)) {
                    Player player;
                    for (int i = p.c.tileMap.players.size() - 1; i >= 0; i--) {
                        player = p.c.tileMap.players.get(i);
                        if (player != null && player.c != null) {
                            p.c.tileMap.sendMounts(p.c.get(), player);
                        }
                    }
                }
            } else if (data.skill > 0) {
                byte skill = data.skill;
                if (item.id == 547) {
                    skill += p.c.get().nclass;
                }
                p.openBookSkill(index, skill);
                return;
            } else {
                byte numbagnull = p.c.getBagNull();
                OUTER:
                switch (item.id) {
                    case 13: {
                        if (p.c.pk > 10 || checkExpDown) {
                            p.sendAddchatYellow(Language.MAX_EXP_DOWN);
                            return;
                        }
                        if (p.buffHP(25)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 14: {
                        if (p.c.pk > 10 || checkExpDown) {
                            p.sendAddchatYellow(Language.MAX_EXP_DOWN);
                            return;
                        }
                        if (p.buffHP(90)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 15: {
                        if (p.c.pk > 10 || checkExpDown) {
                            p.sendAddchatYellow(Language.MAX_EXP_DOWN);
                            return;
                        }
                        if (p.buffHP(230)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 16: {
                        if (p.c.pk > 10 || checkExpDown) {
                            p.sendAddchatYellow(Language.MAX_EXP_DOWN);
                            return;
                        }
                        if (p.buffHP(400)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 17: {
                        if (p.c.pk > 10 || checkExpDown) {
                            p.sendAddchatYellow(Language.MAX_EXP_DOWN);
                            return;
                        }
                        if (p.buffHP(650)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 28: {
                        long expup = (Level.getMaxExp(p.c.get().level + 1) - Level.getMaxExp(p.c.get().level)) / 10;
                        p.updateExp(expup);
                        p.sendAddchatYellow("Bạn nhận được " + expup + " kinh nghiệm.");
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 565: {
                        if (p.c.pk > 10 || checkExpDown) {
                            p.sendAddchatYellow(Language.MAX_EXP_DOWN);
                            return;
                        }
                        if (p.buffHP(1500)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 18: {
                        if (p.c.pk > 10 || checkExpDown) {
                            p.sendAddchatYellow(Language.MAX_EXP_DOWN);
                            return;
                        }
                        if (p.buffMP(150)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 19: {
                        if (p.c.pk > 10 || checkExpDown) {
                            p.sendAddchatYellow(Language.MAX_EXP_DOWN);
                            return;
                        }
                        if (p.buffMP(500)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 20: {
                        if (p.c.pk > 10 || checkExpDown) {
                            p.sendAddchatYellow(Language.MAX_EXP_DOWN);
                            return;
                        }
                        if (p.buffMP(1000)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 21: {
                        if (p.c.pk > 10 || checkExpDown) {
                            p.sendAddchatYellow(Language.MAX_EXP_DOWN);
                            return;
                        }
                        if (p.buffMP(2000)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 22: {
                        if (p.c.pk > 10 || checkExpDown) {
                            p.sendAddchatYellow(Language.MAX_EXP_DOWN);
                            return;
                        }
                        if (p.buffMP(3500)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 566: {
                        if (p.c.pk > 10 || checkExpDown) {
                            p.sendAddchatYellow(Language.MAX_EXP_DOWN);
                            return;
                        }
                        if (p.buffMP(5000)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 23: {
                        if (p.dungThucan((byte) 0, 3, 1800)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 24: {
                        if (p.dungThucan((byte) 1, 20, 1800)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 25: {
                        if (p.dungThucan((byte) 2, 30, 1800)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 26: {
                        if (p.dungThucan((byte) 3, 40, 1800)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 27: {
                        if (p.dungThucan((byte) 4, 50, 1800)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 29: {
                        if (p.dungThucan((byte) 28, 60, 1800)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 30: {
                        if (p.dungThucan((byte) 28, 60, 259200)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 249: {
                        if (p.dungThucan((byte) 3, 40, 259200)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 250: {
                        if (p.dungThucan((byte) 4, 50, 259200)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 409: {
                        if (p.dungThucan((byte) 30, 75, 86400)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 410: {
                        if (p.dungThucan((byte) 31, 90, 86400)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 567: {
                        if (p.dungThucan((byte) 35, 120, 86400)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 34:
                    case 36: {
                        Map map = Manager.getMapid(p.c.mapLTD);
                        if (map != null) {
                            byte i;
                            for (i = 0; i < map.area.length; ++i) {
                                if (map.area[i].numplayers < map.template.maxplayers) {
                                    p.c.tileMap.leave(p);
                                    map.area[i].EnterMap0(p.c);
                                    if (item.id == 34) {
                                        p.c.removeItemBag(index, 1);
                                    }
                                    return;
                                }
                            }
                            break;
                        }
                        break;
                    }
                    case 38: {
                        if (p.c.isNhanban) {
                            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        int per = Util.nextInt(UseItem.idItemPhucNangNhanGia.length);
                        p.c.removeItemBag(index, 1);
                        if (UseItem.idItemPhucNangNhanGia[per] == -1) {
                            long yenran = Util.nextInt(100, 1000);
                            p.c.upyenMessage(yenran);
                            p.sendAddchatYellow("Bạn nhận được " + yenran + " yên.");
                        } else {
                            p.c.addItemBag((UseItem.idItemPhucNangNhanGia[per] == 28), ItemTemplate.itemDefault(UseItem.idItemPhucNangNhanGia[per]));
                        }
                        break;
                    }
                    case 257: {
//                        try {
//                            Thread.sleep(3000);
//                        } catch (InterruptedException e) {
                        if (p.c.get().pk > 0) {
                            p.c.get().pk -= 5;
                            if (p.c.get().pk < 0) {
                                p.c.get().pk = 0;
                            }
                            p.sendAddchatYellow("Điểm hiếu chiến của bạn còn lại là " + p.c.get().pk);
                            p.c.removeItemBag(index, 1);
                            break;
                        }
                        p.sendAddchatYellow("Bạn không có điểm hiếu chiến.");
                        break;
                    }
                    case 222:
                    case 223:
                    case 224:
                    case 225:
                    case 226:
                    case 227:
                    case 228: {
                        if (p.c.nclass == 0) {
                            p.conn.sendMessageLog("Bạn cần nhập học để sử dụng vật phẩm này.");
                            return;
                        }
                        if (p.c.quantityItemyTotal(222) < 1 || p.c.quantityItemyTotal(223) < 1 || p.c.quantityItemyTotal(224) < 1 || p.c.quantityItemyTotal(225) < 1 || p.c.quantityItemyTotal(226) < 1 || p.c.quantityItemyTotal(227) < 1 || p.c.quantityItemyTotal(228) < 1) {
                            p.conn.sendMessageLog("Bạn không có đủ 7 viên ngọc rồng 1-7 sao để nhận Yoroi.");
                            return;
                        }
                        if (p.c.getBagNull() < 1) {
                            p.sendAddchatYellow(Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        synchronized (LOCK) {
                            try {
                                m = new Message(-30);
                                m.writer().writeByte(-58);
                                m.writer().writeInt(p.c.get().id);
                                m.writer().flush();
                                p.conn.sendMessage(m);
                                m.cleanup();

                                m = new Message(-30);
                                m.writer().writeByte(-57);
                                m.writer().flush();
                                p.c.tileMap.sendMessage(m);
                                m.cleanup();
                            } catch (Exception e) {
                                e.printStackTrace();
                            }
                        }
                        int i;
                        byte count = 0;
                        for (i = 222; i <= 228; i++) {
                            if (p.c.getIndexBagid(i, false) != -1) {
                                p.c.removeItemBag(p.c.getIndexBagid(i, false), 1);
                                count++;
                            } else {
                                p.c.removeItemBag(p.c.getIndexBagid(i, true), 1);
                            }
                        }
                        byte nClassC = p.c.get().nclass;
                        if (p.c.isNhanban) {
                            nClassC = p.c.clone.nclass;
                        }
                        p.c.addItemBag(false, ItemTemplate.itemDefault(419 + GameSrc.SysClass(nClassC), count == 7 ? false : true));
                        break;
                    }
//                    case 215:
//                    case 229:
//                    case 283: {
//                        byte level = (byte) ((item.id != 215) ? ((item.id != 229) ? 3 : 2) : 1);
//                        if (level > p.c.levelBag + 1) {
//                            p.sendAddchatYellow("Cần mở Túi vải cấp " + (p.c.levelBag + 1) + " mới có thể mở được túi vải này.");
//                            return;
//                        }
//                        if (p.c.levelBag >= level) {
//                            p.sendAddchatYellow("Bạn đã mở túi vải này rồi.");
//                            return;
//                        }
//                        p.c.levelBag = level;
//                        p.c.maxluggage += UseItem.arrOpenBag[level];
//                        Item[] bag = new Item[p.c.maxluggage];
//                        short j;
//                        for (j = 0; j < p.c.ItemBag.length; ++j) {
//                            bag[j] = p.c.ItemBag[j];
//                        }
//                        (p.c.ItemBag = bag)[index] = null;
//                        p.openBagLevel(index);
//                        break;
//                    }
                    case 240: {
                        p.c.removeItemBag(index, 1);
                        p.c.get().countTayTiemNang++;
                        p.sendAddchatYellow("Số lần tẩy điểm tiềm năng tăng thêm 1");
                        break;
                    }
                    case 241: {
                        p.c.removeItemBag(index, 1);
                        p.c.get().countTayKyNang++;
                        p.sendAddchatYellow("Số lần tẩy điểm kỹ năng tăng thêm 1");
                        break;
                    }
                    case 248: {
                        Effect eff = p.c.get().getEffId(22);
                        if (eff != null) {
                            long time = eff.timeRemove + 18000000L;
                            p.setEffect(22, 0, (int) (time - System.currentTimeMillis()), 2);
                        } else {
                            p.setEffect(22, 0, 18000000, 2);
                        }
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 251: {
                        if (item.quantity < 300) {
                            p.sendAddchatYellow("Bạn cần ít nhất 300 mảnh giấy vụn mới có thể sử dụng.");
                            return;
                        }
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
                            return;
                        }
                        p.typemenu = -125;
                        Service.doMenuArray(p, new String[]{"Sách kỹ năng sơ", "Sách tiềm năng sơ"});
                        break;
                    }
                    case 252: { // sách kỹ năng 
                        if (p.c.get().isNhanban) {
                            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.useKyNang < 3) {
                            p.c.useKyNang++;
                            p.c.spoint += 1;
                            p.loadSkill();
                            p.c.removeItemBag(index, 1);
                            p.sendAddchatYellow("Bạn nhận được 1 điểm kỹ năng.");
                        } else {
                            p.sendAddchatYellow("Bạn chỉ được học 3 lần.");
                        }
                        break;
                    }
                    case 253: { // sách tiềm năng
                        if (p.c.get().isNhanban) {
                            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.useTiemNang < 8) {
                            p.c.useTiemNang++;
                            p.c.ppoint += 10;
                            p.loadPpoint();
                            p.c.removeItemBag(index, 1);
                            p.sendAddchatYellow("Bạn nhận được 10 điểm tiềm năng.");
                        } else {
                            p.sendAddchatYellow("Bạn chỉ được học 8 lần.");
                        }
                        break;
                    }
                    case 254:
                    case 255:
                    case 256: {
                        if (p.c.expdown == 0) {
                            p.conn.sendMessageLog("Bạn không có kinh nhiệm âm để sử dụng vật phẩm này!");
                            return;
                        }
                        if (item.id == 254 && p.c.level >= 30) {
                            p.conn.sendMessageLog("Trình độ của bạn không phù hợp để sử dụng vật phẩm này.");
                            return;
                        }
                        if (item.id == 255 && (p.c.level < 30 || p.c.level >= 60)) {
                            p.conn.sendMessageLog("Trình độ của bạn không phù hợp để sử dụng vật phẩm này.");
                            return;
                        }
                        if (item.id == 256 && p.c.level < 60) {
                            p.conn.sendMessageLog("Trình độ của bạn không phù hợp để sử dụng vật phẩm này.");
                            return;
                        }
                        p.updateExp(p.c.expdown);
                        p.sendAddchatYellow("Kinh nghiệm âm của bạn đã được xoá.");
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 261: {
                        if (p.c.isNhanban) {
                            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (!p.c.tileMap.map.mapLDGT()) {
                            p.sendAddchatYellow("Vật phẩm chỉ có thể được dùng trong Lãnh Địa Gia Tộc.");
                            return;
                        }
                        p.setEffect(23, 0, 300000, 2000);
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 263: {
                        if (p.c.isNhanban) {
                            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        int per = Util.nextInt(UseItem.idItemTuiQuaGiaToc.length);
                        p.c.addItemBag(true, ItemTemplate.itemDefault(UseItem.idItemTuiQuaGiaToc[per]));
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 268: {
                        if (p.c.isNhanban) {
                            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.useTaThuLenh == 0) {
                            p.conn.sendMessageLog("Số lần sử dụng lệnh bài hang động của bạn hôm nay đã hết.");
                            return;
                        }
                        p.c.useTaThuLenh--;
                        p.c.countTaskTaThu -= 2;
                        p.sendAddchatYellow("Số lần nhận nhiệm vụ tà thú tăng thêm 2 lần");
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 275: {
                        p.setEffect(24, 0, 600000, 500);
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 276: {
                        p.setEffect(25, 0, 600000, 500);
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 277: {
                        p.setEffect(26, 0, 600000, 100);
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 278: {
                        p.setEffect(27, 0, 600000, 1000);
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case ItemName.GIAY_CHUNG_NHAN_THO_XAY: {
                        p.setEffect(44, 0, 86400000, 0);
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case ItemName.CHIA_KHOA_NHA: {
                        if (p.c.mapid != 193) {
                            Map ma = Manager.getMapid(193);
                            int maxPlayers = ma.template.maxplayers;
                            for (TileMap area : ma.area) {
                                if (area.numplayers < maxPlayers) {
                                    p.c.tileMap.leave(p);
                                    area.EnterMap0(p.c);
                                    return;
                                }
                            }
                        } else {
                            Map ma = Manager.getMapid(22);
                            int maxPlayers = ma.template.maxplayers;
                            for (TileMap area : ma.area) {
                                if (area.numplayers < maxPlayers) {
                                    p.c.tileMap.leave(p);
                                    area.EnterMap0(p.c);
                                    return;
                                }
                            }
                        }
                    }
                    case 280: {
                        if (p.c.useCave == 0) {
                            p.conn.sendMessageLog("Số lần sử dụng lệnh bài hang động của bạn hôm nay đã hết.");
                            return;
                        }
                        p.c.nCave++;
                        p.c.useCave--;
                        p.sendAddchatYellow("Số lần vào hang động tăng thêm 1 lần, hôm nay bạn chỉ cần có thể sử dụng lệnh bài " + p.c.useCave + " lần");
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                   
                    case 272: {
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
                            return;
                        }
                        p.c.removeItemBag(index, 1);
                        if (Util.nextInt(3) == 0) {
                            int num = Util.nextInt(10000, 30000);
                            p.c.upyenMessage(num);
                            p.sendAddchatYellow("Bạn nhận được " + num + " yên");
                        } else {
                            short idI = UseItem.idItemRuongMayMan[Util.nextInt(UseItem.idItemRuongMayMan.length)];
                            ItemTemplate data2 = ItemTemplate.ItemTemplateId(idI);
                            Item itemup;
                            if (data2.type < 10) {
                                if (data2.type == 1) {
                                    itemup = ItemTemplate.itemDefault(idI);
                                    itemup.sys = GameSrc.SysClass(data2.nclass);
                                } else {
                                    byte sys = (byte) Util.nextInt(1, 3);
                                    itemup = ItemTemplate.itemDefault(idI, sys);
                                }
                            } else {
                                itemup = ItemTemplate.itemDefault(idI);
                            }
                            itemup.isLock = item.isLock;
                            int idOp2;
                            for (Option Option : itemup.options) {
                                idOp2 = Option.id;
                                Option.param = Util.nextInt(item.getOptionShopMin(idOp2, Option.param), Option.param);
                            }
                            p.c.addItemBag(true, itemup);
                        }
                        break;
                    }
                    case 282: {
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
                            return;
                        }
                        if (Util.nextInt(3) == 0) {
                            int num = Util.nextInt(10000, 30000);
                            p.c.upyenMessage(num);
                            p.sendAddchatYellow("Bạn nhận được " + num + " yên");
                        } else {
                            short idI = UseItem.idItemRuongTinhXao[Util.nextInt(UseItem.idItemRuongTinhXao.length)];
                            ItemTemplate data2 = ItemTemplate.ItemTemplateId(idI);
                            Item itemup;
                            if (data2.type < 10) {
                                if (data2.type == 1) {
                                    itemup = ItemTemplate.itemDefault(idI);
                                    itemup.sys = GameSrc.SysClass(data2.nclass);
                                } else {
                                    byte sys = (byte) Util.nextInt(1, 3);
                                    itemup = ItemTemplate.itemDefault(idI, sys);
                                }
                            } else {
                                itemup = ItemTemplate.itemDefault(idI);
                            }
                            itemup.isLock = item.isLock;
                            for (Option Option : itemup.options) {
                                int idOp2 = Option.id;
                                Option.param = Util.nextInt(item.getOptionShopMin(idOp2, Option.param), Option.param);
                            }
                            p.c.addItemBag(true, itemup);
                        }
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case ItemName.RUONG_NGOC: { // rương ngọc
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành Trang Không Đủ Chỗ Trống");
                            return;
                        }
                        final short[] arId = {652, 653, 654, 655};
                        final short idI = arId[Util.nextInt(arId.length)];
                        final Item Itemup = ItemTemplate.itemDefault(idI);
                        p.c.addItemBag(true, Itemup);
                        p.c.removeItemBags(ItemName.RUONG_NGOC, 1);
                        break;
                    }
                    case 298:
                    case 299:
                    case 300:
                    case 301:
                        ItemHandleUseEvent.Banh(p, item, index);
                        break;
                    case 302:
                        ItemHandleUseEvent.HopBanhThuong(p, item, index);
                        break;
                    case 303:
                        ItemHandleUseEvent.HopBanhThuongHang(p, item, index);
                        break;
                    case 867:
                        ItemHandleUseEvent.LongDen(p, item, index);
                        break;
                    case ItemName.KEO_TAO:
                        ItemHandleUseEvent.KeoTao(p, item, index);
                        break;
                    case ItemName.HOP_MA_QUY:
                        ItemHandleUseEvent.HopMaQuy(p, item, index);
                        break;
                    case ItemName.BANH_KHUC_CAY_DAU_TAY:
                        ItemHandleUseEvent.BanhKhucDauTay(p, item, index);
                        break;
                    case ItemName.BANH_KHUC_CAY_CHOCOLATE:
                        ItemHandleUseEvent.BanhKhucChocolate(p, item, index);
                        break;
                    case ItemName.TRE_XANH_TRAM_DOT:
                        ItemHandleUseEvent.HopBanhThuong(p, item, index);
                        break;
                    case ItemName.TRE_VANG_TRAM_DOT:
                        ItemHandleUseEvent.HopBanhThuongHang(p, item, index);
                        break;
                    case ItemName.DUA_HAU_DAI:
                        if (p.c.level < 40) {
                        p.conn.sendMessageLog("yêu Cầu Trình Độ Cấp 40");
                        return;
                        }
                        ItemHandleUseEvent.DuaHauDai(p, item, index);
                        break;
                    case ItemName.DUA_HAU_TRON:
                        if (p.c.level < 40) {
                        p.conn.sendMessageLog("yêu Cầu Trình Độ Cấp 40");
                        return;
                        }
                        ItemHandleUseEvent.DuaHauTron(p, item, index);
                        break;
                    case ItemName.BO_SEN_TRANG:
                        ItemHandleUseEvent.BoSenTrang(p, item, index);
                        break;
                    case ItemName.BO_SEN_HONG:
                        ItemHandleUseEvent.BoSenHong(p, item, index);
                        break;
                    case ItemName.KHAI_THU_LENH: // mở chỉ số mới cho thú cưỡi
                        ItemHandleUse.KhaiThuLenh(p, item, index);
                        break;
                    case 819:
                        ItemHandleUseEvent.HomMayMan(p, item, index);
                        break;
                    case ItemName.HOA_TUYET:
                        ItemHandleUse.HoaTuyet(p, item, index);
                        break;
                    case ItemName.NHAM_THACH:
                        ItemHandleUse.NhamThach(p, item, index);
                        break;
                    case ItemName.PHA_LE:
                        ItemHandleUse.PhaLe(p, item, index);
                        break;
                    case 308: {
                        if (p.c.get().isNhanban) {
                            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.useBanhPhongLoi < 10) {
                            p.c.useBanhPhongLoi++;
                            p.c.spoint += 1;
                            p.loadSkill();
                            p.c.removeItemBag(index, 1);
                            p.sendAddchatYellow("Bạn nhận được 1 điểm kỹ năng.");
                        } else {
                            p.sendAddchatYellow("Bạn chỉ được học 10 lần.");
                        }
                        break;
                    }
                    case 309: {
                        if (p.c.get().isNhanban) {
                            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.useBanhBangHoa < 10) {
                            p.c.useBanhBangHoa++;
                            p.c.ppoint += 10;
                            p.loadPpoint();
                            p.c.removeItemBag(index, 1);
                            p.sendAddchatYellow("Bạn nhận được 10 điểm tiềm năng.");
                        } else {
                            p.sendAddchatYellow("Bạn chỉ được học 10 lần.");
                        }
                        break;
                    }
                    case 383:
                    case 384:
                    case 385:
                        ItemHandleUse.RuongHuyenBiBatBaoBachNgan(p, item, index);
                        break;
                    case 436:
                    case 437:
                    case 438: {
                        ClanManager clan = ClanManager.getClanName(p.c.clan.clanName);
                        if (clan == null || clan.getMem(p.c.name) == null) {
                            p.sendAddchatYellow("Cần có gia tộc để sử dụng");
                            return;
                        }
                        switch (item.id) {
                            case 436:
                                if (clan.level < 1) {
                                    p.sendAddchatYellow("Yêu cầu gia tộc phải đạt cấp 5");
                                    return;
                                }
                                p.upExpClan(Util.nextInt(100, 200));
                                p.c.removeItemBag(index, 1);
                                break;
                            case 437:
                                if (clan.level < 10) {
                                    p.sendAddchatYellow("Yêu cầu gia tộc phải đạt cấp 10");
                                    return;
                                }
                                p.upExpClan(Util.nextInt(300, 800));
                                p.c.removeItemBag(index, 1);
                                break;
                            case 438:
                                if (clan.level < 15) {
                                    p.sendAddchatYellow("Yêu cầu gia tộc phải đạt cấp 15");
                                    return;
                                }
                                p.upExpClan(Util.nextInt(1000, 2000));
                                p.c.removeItemBag(index, 1);
                                break;
                        }
                        break;
                    }
                    
                    case 454: {
                        if (p.updateSysMounts()) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 490: {
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.pk > 0) {
                            p.sendAddchatYellow("Không thể vào làng cổ khi có điểm hiếu chiến lớn hơn 0");
                            return;
                        }
                        p.c.removeItemBag(index, 1);
                        p.c.tileMap.leave(p);
                        Map map = Server.maps[138];
                        byte k;
                        for (k = 0; k < map.area.length; k++) {
                            if (map.area[k].numplayers < map.template.maxplayers) {
                                map.area[k].EnterMap0(p.c);
                                break;
                            }
                        }
                        p.endLoad(true);
                        break;
                    }
                     case 956: {
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (item.quantity < 5000) {
                            p.sendAddchatYellow("Bạn cần có 5000 mảnh mắt rồng để tiến hoá lên cánh rồng");
                            return;
                        }
                        p.c.removeItemBag(index, 5000);
                        p.c.addItemBag(true, ItemTemplate.itemDefault(958, false));
                        break;
                    }
                     case 1021: {
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (item.quantity < 5000) {
                            p.sendAddchatYellow("Bạn cần có 5000 mảnh Kakashi xiên Zin để thức tỉnh saringan");
                            return;
                        }
                        p.c.removeItemBag(index, 5000);
                        p.c.addItemBag(true, ItemTemplate.itemDefault(1017, false));
                        break;
                    }
                     case 658: {
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (item.quantity < 5000) {
                            p.sendAddchatYellow("M cần có 5k đớ năng lượng gió để đổi mn ");
                            return;
                        }
                        p.c.removeItemBag(index, 5000);
                        p.c.addItemBag(true, ItemTemplate.itemDefault(960, false));
                        break;
                    }
                    case 656: {
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (item.quantity < 5000) {
                            p.sendAddchatYellow("M cần có 5k đớ năng lượng băng để đổi mn");
                            return;
                        }
                        p.c.removeItemBag(index, 5000);
                        p.c.addItemBag(true, ItemTemplate.itemDefault(964, false));
                        break;
                    }
                    case 657: {
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (item.quantity < 5000) {
                            p.sendAddchatYellow("M cần có 5k đớ năng lượng hoả để đổi mn");
                            return;
                        }
                        p.c.removeItemBag(index, 5000);
                        p.c.addItemBag(true, ItemTemplate.itemDefault(963, false));
                        break;
                    }
//                     case 1022: {
//                        byte level = (byte) ((item.id != 215) ? ((item.id != 229) ? ((item.id != 283) ? 4 : 3) : 2) : 1);
//                        if (level > p.c.levelBag + 1) {
//                            p.sendAddchatYellow("Cần mở Túi vải cấp " + (p.c.levelBag + 1) + " mới có thể mở được túi vải này.");
//                            return;
//                        }
//                        if (p.c.levelBag >= level) {
//                            p.sendAddchatYellow("Bạn đã mở túi vải này rồi.");
//                            return;
//                        }
//                        p.c.levelBag = level;
//                        p.c.maxluggage += UseItem.arrOpenBag[level];
//                        Item[] bag = new Item[p.c.maxluggage];
//                        short j;
//                        for (j = 0; j < p.c.ItemBag.length; ++j) {
//                            bag[j] = p.c.ItemBag[j];
//                        }
//                        (p.c.ItemBag = bag)[index] = null;
//                        p.openBagLevel(index);
//                        break;
//                    }
                     case 1023: {
                        if (p.c.isNhanban) {
                            p.sendAddchatYellow("Phân thân không thể sử dụng vật phẩm này.");
                            return;
                        }
//                        if (p.c.get().useKyNang != 0) {
//                            p.sendAddchatYellow("Bạn phải học hết số sách sơ cấp đã");
//                            return;
//                        }
                        if (p.c.kntrung < 1) {
                            p.sendAddchatYellow("Bạn đã hết số lần sử dụng vật phẩm này.");
                            return;
                        }
                        p.c.kntrung--;
                        p.c.get().spoint += 2;
                        p.c.removeItemBag(index, 1);
                        p.loadSkill();
                        p.sendAddchatYellow("Bạn nhận được 2 điểm kỹ năng.");
                        break;
                    }
                    //sách tiềm năng trung
                    case 1024: {
                        if (p.c.isNhanban) {
                            p.sendAddchatYellow("Phân thân không thể sử dụng vật phẩm này.");
                            return;
                        }
//                        if (p.c.get().useTiemNang != 0) {
//                            p.sendAddchatYellow("Bạn phải học hết số sách sơ cấp đã.");
//                            return;
//                        }
//                        if (p.c.tntrung < 1) {
//                            p.sendAddchatYellow("Bạn đã hết số lần sử dụng vật phẩm này.");
//                            return;
//                        }
                        p.c.tntrung--;
                        p.c.get().ppoint += 50;
                        p.loadPpoint();
                        p.c.removeItemBag(index, 1);
                        p.sendAddchatYellow("Bạn nhận được 50 điểm tiềm năng.");
                        break;
                    }
                case 1046: {
                      
//                        if (p.c.getEffId(34) == null) {
//                    p.conn.sendMessageLog("Phải sử dụng thí luyện thiếp mới có thể vào.");
//                    return;
//                }
//                        if (p.c.pk > 0) {
//                            p.sendAddchatYellow("Tẩy hiếu chiến đi xong tao cho dùng nhé!");
//                            return;
//                        }
//                        p.c.removeItemBag(index, 1);
//                            if (p.c.id != 97 || p.c.id != 201) {
//                                p.conn.sendMessageLog("Bơm a 5 Lít a cho vào.");
//                    return;
//                            }
                            if (p.vip != 6) {
                           p.sendAddchatYellow("Nạp 5 lít vào");
//Service.chatNPC(p, (short) npcid, "Bạn không đủ điều kiện nhận VIP");
                            return;
                        }
                            if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.pk > 0) {
                            p.sendAddchatYellow("Không thể vào khi có điểm hiếu chiến lớn hơn 0");
                            return;
                        }
//                        p.c.removeItemBag(index, 1);
                        p.c.tileMap.leave(p);
                        Map map = Server.maps[197];
                        byte k;
                        for (k = 0; k < map.area.length; k++) {
                            if (map.area[k].numplayers < map.template.maxplayers) {
                                map.area[k].EnterMap0(p.c);
                                break;
                            }
                        }
                        p.endLoad(true);
                        break;
                    }
//                        p.c.tileMap.leave(p);
//                        Map map = Server.maps[197];
//                        byte k;
//                        for (k = 0; k < map.area.length; k++) {
//                            if (map.area[k].numplayers < map.template.maxplayers) {
//                                map.area[k].EnterMap0(p.c);
//                                break;
//                            }
//                        }
//                        p.endLoad(true);
//                        break;
//                    }//mới thêm
                case 997: {
//                       if (p.c.getEffId(34) == null) {
              
                        if (p.vip != 6) {
                           p.sendAddchatYellow("Nạp 5 lít vào");
//Service.chatNPC(p, (short) npcid, "Bạn không đủ điều kiện nhận VIP");
                            return;
                        }
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.pk > 0) {
                            p.sendAddchatYellow("Không thể vào khi có điểm hiếu chiến lớn hơn 0");
                            return;
                        }
//                        p.c.removeItemBag(index, 1);
                        p.c.tileMap.leave(p);
                        Map map = Server.maps[112];
                        byte k;
                        for (k = 0; k < map.area.length; k++) {
                            if (map.area[k].numplayers < map.template.maxplayers) {
                                map.area[k].EnterMap0(p.c);
                                break;
                            }
                        }
                        p.endLoad(true);
                        break;
                    }
////                        p.c.removeItemBag(index, 1);
//                        p.c.tileMap.leave(p);
//                        Map map = Server.maps[112];
//                        byte k;
//                        for (k = 0; k < map.area.length; k++) {
//                            if (map.area[k].numplayers < map.template.maxplayers) {
//                                map.area[k].EnterMap0(p.c);
//                                break;
//                            }
//                        }
//                        p.endLoad(true);
//                        break;
//                    }
                case 990: {
//                       if (p.c.getEffId(34) == null) {
              
                        if (p.vip != 6) {
                           p.sendAddchatYellow("Nạp 5 lít vào");
//Service.chatNPC(p, (short) npcid, "Bạn không đủ điều kiện nhận VIP");
                            return;
                        }
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.pk > 0) {
                            p.sendAddchatYellow("Không thể vào  khi có điểm hiếu chiến lớn hơn 0");
                            return;
                        }
//                        p.c.removeItemBag(index, 1);
                        p.c.tileMap.leave(p);
                        Map map = Server.maps[199];
                        byte k;
                        for (k = 0; k < map.area.length; k++) {
                            if (map.area[k].numplayers < map.template.maxplayers) {
                                map.area[k].EnterMap0(p.c);
                                break;
                            }
                        }
                        p.endLoad(true);
                        break;
                    }
                case 1050: {
//                       if (p.c.getEffId(34) == null) {
              
                        if (p.vip != 6) {
                           p.sendAddchatYellow("Nạp 5 lít vào");
//Service.chatNPC(p, (short) npcid, "Bạn không đủ điều kiện nhận VIP");
                            return;
                        }
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.pk > 0) {
                            p.sendAddchatYellow("Không thể vào  khi có điểm hiếu chiến lớn hơn 0");
                            return;
                        }
//                        p.c.removeItemBag(index, 1);
                        p.c.tileMap.leave(p);
                        Map map = Server.maps[202];
                        byte k;
                        for (k = 0; k < map.area.length; k++) {
                            if (map.area[k].numplayers < map.template.maxplayers) {
                                map.area[k].EnterMap0(p.c);
                                break;
                            }
                        }
                        p.endLoad(true);
                        break;
                    }
//                        p.c.removeItemBag(index, 1);
//                        p.c.tileMap.leave(p);
//                        Map map = Server.maps[199];
//                        byte k;
//                        for (k = 0; k < map.area.length; k++) {
//                            if (map.area[k].numplayers < map.template.maxplayers) {
//                                map.area[k].EnterMap0(p.c);
//                                break;
//                            }
//                        }
//                        p.endLoad(true);
//                        break;
//                    }
                 case 1035: {
//                       if (p.c.getEffId(34) == null) {
              
                        if (p.vip != 6) {
                           p.sendAddchatYellow("Nạp 5 lít vào");
//Service.chatNPC(p, (short) npcid, "Bạn không đủ điều kiện nhận VIP");
                            return;
                        }
//                        p.c.removeItemBag(index, 1);
//                        p.c.tileMap.leave(p);
//                        Map map = Server.maps[207];
//                        byte k;
//                        for (k = 0; k < map.area.length; k++) {
//                            if (map.area[k].numplayers < map.template.maxplayers) {
//                                map.area[k].EnterMap0(p.c);
//                                break;
//                            }
//                        }
//                        p.endLoad(true);
//                        break;
//                    }
if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.pk > 0) {
                            p.sendAddchatYellow("Không thể vào làng cổ khi có điểm hiếu chiến lớn hơn 0");
                            return;
                        }
//                        p.c.removeItemBag(index, 1);
                        p.c.tileMap.leave(p);
                        Map map = Server.maps[207];
                        byte k;
                        for (k = 0; k < map.area.length; k++) {
                            if (map.area[k].numplayers < map.template.maxplayers) {
                                map.area[k].EnterMap0(p.c);
                                break;
                            }
                        }
                        p.endLoad(true);
                        break;
                    }
                  case 1034: {
//                       if (p.c.getEffId(34) == null) {
              
                        if (p.vip != 6) {
                           p.sendAddchatYellow("Nạp 5 lít vào");
//Service.chatNPC(p, (short) npcid, "Bạn không đủ điều kiện nhận VIP");
                            return;
                        }
//                        p.c.removeItemBag(index, 1);
//                        p.c.tileMap.leave(p);
//                        Map map = Server.maps[208];
//                        byte k;
//                        for (k = 0; k < map.area.length; k++) {
//                            if (map.area[k].numplayers < map.template.maxplayers) {
//                                map.area[k].EnterMap0(p.c);
//                                break;
//                            }
//                        }
//                        p.endLoad(true);
//                        break;
//                    }
if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.pk > 0) {
                            p.sendAddchatYellow("Không thể vào làng cổ khi có điểm hiếu chiến lớn hơn 0");
                            return;
                        }
//                        p.c.removeItemBag(index, 1);
                        p.c.tileMap.leave(p);
                        Map map = Server.maps[208];
                        byte k;
                        for (k = 0; k < map.area.length; k++) {
                            if (map.area[k].numplayers < map.template.maxplayers) {
                                map.area[k].EnterMap0(p.c);
                                break;
                            }
                        }
                        p.endLoad(true);
                        break;
                    }
                case 1045: {
//                       if (p.c.getEffId(34) == null) {
              
                        if (p.vip != 6) {
                           p.sendAddchatYellow("Nạp 5 lít vào");
//Service.chatNPC(p, (short) npcid, "Bạn không đủ điều kiện nhận VIP");
                            return;
                        }
//                        p.c.removeItemBag(index, 1);
//                        p.c.tileMap.leave(p);
//                        Map map = Server.maps[196];
//                        byte k;
//                        for (k = 0; k < map.area.length; k++) {
//                            if (map.area[k].numplayers < map.template.maxplayers) {
//                                map.area[k].EnterMap0(p.c);
//                                break;
//                            }
//                        }
//                        p.endLoad(true);
//                        break;
//                    }
if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.pk > 0) {
                            p.sendAddchatYellow("Không thể vào khi có điểm hiếu chiến lớn hơn 0");
                            return;
                        }
//                        p.c.removeItemBag(index, 1);
                        p.c.tileMap.leave(p);
                        Map map = Server.maps[196];
                        byte k;
                        for (k = 0; k < map.area.length; k++) {
                            if (map.area[k].numplayers < map.template.maxplayers) {
                                map.area[k].EnterMap0(p.c);
                                break;
                            }
                        }
                        p.endLoad(true);
                        break;
                    }
                
//                case 997: {
//                       if (p.c.getEffId(34) == null) {
//                    p.conn.sendMessageLog("Phải sử dụng thí luyện thiếp mới có thể vào.");
//                    return;
//                }
//                        if (p.c.pk > 0) {
//                            p.sendAddchatYellow("Tẩy hiếu chiến đi xong tao cho dùng nhé!");
//                            return;
//                        }
//                        p.c.removeItemBag(index, 1);
//                        p.c.tileMap.leave(p);
//                        Map map = Server.maps[112];
//                        byte k;
//                        for (k = 0; k < map.area.length; k++) {
//                            if (map.area[k].numplayers < map.template.maxplayers) {
//                                map.area[k].EnterMap0(p.c);
//                                break;
//                            }
//                        }
//                        p.endLoad(true);
//                        break;
//                    }
                case 991: {
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
                            return;
                        }
                        if (p.c.level < 100) {
                            p.sendAddchatYellow("Trình độ của bạn không đủ để sử dụng vật phẩm này.");
                            return;
                        }
                        short idI = UseItem.RUONG_SACH_VO_CONG_10X[Util.nextInt(UseItem.RUONG_SACH_VO_CONG_10X.length)];
                        ItemTemplate data2 = ItemTemplate.ItemTemplateId(idI);
                        Item itemup = ItemTemplate.itemDefault(idI);
                        if (data2.type < 10) {
                            if (data2.type == 1) {
                                itemup = ItemTemplate.itemDefault(idI);
                                itemup.sys = GameSrc.SysClass(data2.nclass);
                            } else {
                                itemup.sys = (byte) Util.nextInt(1, 3);
                                itemup = ItemTemplate.itemDefault((int) idI, itemup.sys);
                            }
                        } else {
                            itemup = ItemTemplate.itemDefault(idI);
                        }
                        p.c.addItemBag(false, itemup);
                        p.c.removeItemBag(index, 1);
                        break;
                    } 
                case 1043: {
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
                            return;
                        }
                        if (p.c.level < 120) {
                            p.sendAddchatYellow("Trình độ của bạn không đủ để sử dụng vật phẩm này.");
                            return;
                        }
                        short idI = UseItem.tb125[Util.nextInt(UseItem.tb125.length)];
                        ItemTemplate data2 = ItemTemplate.ItemTemplateId(idI);
                        Item itemup = ItemTemplate.itemDefault(idI);
                        if (data2.type < 10) {
                            if (data2.type == 1) {
                                itemup = ItemTemplate.itemDefault(idI);
                                itemup.sys = GameSrc.SysClass(data2.nclass);
                            } else {
                                itemup.sys = (byte) Util.nextInt(1, 3);
                                itemup = ItemTemplate.itemDefault((int) idI, itemup.sys);
                            }
                        } else {
                            itemup = ItemTemplate.itemDefault(idI);
                        }
                        p.c.addItemBag(false, itemup);
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                case 1044: {
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
                            return;
                        }
                        if (p.c.level < 10) {
                            p.sendAddchatYellow("Trình độ của bạn không đủ để sử dụng vật phẩm này.");
                            return;
                        }
                        short idI = UseItem.dh[Util.nextInt(UseItem.dh.length)];
                        ItemTemplate data2 = ItemTemplate.ItemTemplateId(idI);
                        Item itemup = ItemTemplate.itemDefault(idI);
                        if (data2.type < 10) {
                            if (data2.type == 1) {
                                itemup = ItemTemplate.itemDefault(idI);
                                itemup.sys = GameSrc.SysClass(data2.nclass);
                            } else {
                                itemup.sys = (byte) Util.nextInt(1, 3);
                                itemup = ItemTemplate.itemDefault((int) idI, itemup.sys);
                            }
                        } else {
                            itemup = ItemTemplate.itemDefault(idI);
                        }
                        p.c.addItemBag(false, itemup);
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                case 992: {
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
                            return;
                        }
                        if (p.c.level < 120) {
                            p.sendAddchatYellow("Trình độ của bạn không đủ để sử dụng vật phẩm này.");
                            return;
                        }
                        short idI = UseItem.svc12x[Util.nextInt(UseItem.svc12x.length)];
                        ItemTemplate data2 = ItemTemplate.ItemTemplateId(idI);
                        Item itemup = ItemTemplate.itemDefault(idI);
                        if (data2.type < 10) {
                            if (data2.type == 1) {
                                itemup = ItemTemplate.itemDefault(idI);
                                itemup.sys = GameSrc.SysClass(data2.nclass);
                            } else {
                                itemup.sys = (byte) Util.nextInt(1, 3);
                                itemup = ItemTemplate.itemDefault((int) idI, itemup.sys);
                            }
                        } else {
                            itemup = ItemTemplate.itemDefault(idI);
                        }
                        p.c.addItemBag(false, itemup);
                        p.c.removeItemBag(index, 1);
                        break;
                    } 
                    case 865: {
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.pk > 0) {
                            p.sendAddchatYellow("Không thể vào Hang Truyền Thuyết khi có điểm hiếu chiến lớn hơn 0");
                            return;
                        }
                        if (p.c.level < 100) {
                            p.sendAddchatYellow("Yêu cầu trình độ cấp 100");
                            return;
                        }
                        p.c.removeItemBag(index, 1);
                        p.c.tileMap.leave(p);
                        Map map = Server.maps[113];
                        byte k;
                        for (k = 0; k < map.area.length; k++) {
                            if (map.area[k].numplayers < map.template.maxplayers) {
                                map.area[k].EnterMap0(p.c);
                                break;
                            }
                        }
                        p.endLoad(true);
                        break;
                    }
                    case 543: {
                        if (p.c.isNhanban) {
                            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        p.c.removeItemBag(index, 1);
                        p.c.tileMap.leave(p);
                        Map map = Server.maps[113];
                        byte k;
                        for (k = 0; k < map.area.length; k++) {
                            if (map.area[k].numplayers < map.template.maxplayers) {
                                map.area[k].EnterMap0(p.c);
                                break;
                            }
                        }
                        p.endLoad(true);
                        break;
                    }
                    case 537: {
                        if (p.c.get().getEffId(40) == null) {
                            p.setEffect(41, 0, 7200000, 0);
                            p.c.removeItemBag(index, 1);
                        } else {
                            p.sendAddchatYellow("Bạn đã có hiệu quả cao hơn");
                        }
                        break;
                    }
                    case 538: {
                        p.setEffect(40, 0, 18000000, 0);
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 539: {
                        p.setEffect(32, 0, 3600000, 3);
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 540: {
                        p.setEffect(33, 0, 3600000, 4);
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 549:
                    case 550:
                    case 551: {
                        long yenup = 0L;
                        yenup = (Util.nextInt(10000, 30000));;
                        if (item.id == 550) {
                            yenup = (Util.nextInt(20000, 50000));;
                        }
                        if (item.id == 551) {
                            yenup = (Util.nextInt(30000, 100000));;
                        }
                        p.c.upyenMessage(yenup);
                        p.sendAddchatYellow("Bạn nhận được " + yenup + " yên.");
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 564:
                        ItemHandleUse.ThiLuyenThiep(p, item, index);
                        break;
                    case 449: {
                        if (p.updateXpMounts(5, (byte) 0)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 450: {
                        if (p.updateXpMounts(7, (byte) 0)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 451: {
                        if (p.updateXpMounts(14, (byte) 0)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 573: {
                        if (p.updateXpMounts(200, (byte) 0)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 574: {
                        if (p.updateXpMounts(400, (byte) 0)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 575: {
                        if (p.updateXpMounts(600, (byte) 0)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 778: { // exp trâu + bạch hổ
                        if (p.updateXpMounts(100, (byte) 2)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 576: {
                        if (p.updateXpMounts(100, (byte) 1)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 577: {
                        if (p.updateXpMounts(250, (byte) 1)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
                    case 578: {
                        if (p.updateXpMounts(500, (byte) 1)) {
                            p.c.removeItemBag(index, 1);
                        }
                        break;
                    }
//                    case 279:
//                        Service.sendInputDialog(p, (short) 1, "Tên Nhân Vật Cần Đến");
//                        break;
                    case 647: {
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
                            return;
                        }

                        p.c.removeItemBag(index, 1);
                        if (Util.nextInt(3) == 0) {
                            int num = Util.nextInt(10000, 100000);
                            if (Util.percent(1000, 10)) {
                                p.c.addItemBag(true, ItemTemplate.itemDefault(222, false));
                            }
                            p.c.upyenMessage(num);
                            p.sendAddchatYellow("Bạn nhận được " + num + " yên");
                            short idI = UseItem.idItemRuongMaQuai[Util.nextInt(UseItem.idItemRuongMaQuai.length)];
                            ItemTemplate data2 = ItemTemplate.ItemTemplateId(idI);
                            Item itemup;
                            if (data2.type < 10) {
                                if (data2.type == 1) {
                                    itemup = ItemTemplate.itemDefault(idI);
                                    itemup.sys = GameSrc.SysClass(data2.nclass);
                                } else {
                                    byte sys = (byte) Util.nextInt(1, 3);
                                    itemup = ItemTemplate.itemDefault(idI, sys);
                                }
                            } else {
                                itemup = ItemTemplate.itemDefault(idI);
                            }
                            itemup.isLock = false;
                            int idOp2;
                            for (Option Option : itemup.options) {
                                idOp2 = Option.id;
                                Option.param = Util.nextInt(item.getOptionShopMin(idOp2, Option.param), Option.param);
                            }
                            p.c.addItemBag(true, itemup);
                        }
                        break;
                    }
                    case 993: {
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
                            return;
                        }
                        if (p.c.level < 100) {
                            p.sendAddchatYellow("Trình độ của bạn không đủ để sử dụng vật phẩm này.");
                            return;
                        }
                        short idI = UseItem.tb10x[Util.nextInt(UseItem.tb10x.length)];
                        ItemTemplate data2 = ItemTemplate.ItemTemplateId(idI);
                        Item itemup = ItemTemplate.itemDefault(idI);
                        if (data2.type < 10) {
                            if (data2.type == 1) {
                                itemup = ItemTemplate.itemDefault(idI);
                                itemup.sys = GameSrc.SysClass(data2.nclass);
                            } else {
                                itemup.sys = (byte) Util.nextInt(1, 3);
                                itemup = ItemTemplate.itemDefault((int) idI, itemup.sys);
                            }
                        } else {
                            itemup = ItemTemplate.itemDefault(idI);
                        }
                        p.c.addItemBag(false, itemup);
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    //
                    
                    case 996: {
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
                            return;
                        }
                        if (p.c.level < 100) {
                            p.sendAddchatYellow("Trình độ của bạn không đủ để sử dụng vật phẩm này.");
                            return;
                        }
                        short idI = UseItem.vk10x[Util.nextInt(UseItem.vk10x.length)];
                        ItemTemplate data2 = ItemTemplate.ItemTemplateId(idI);
                        Item itemup = ItemTemplate.itemDefault(idI);
                        if (data2.type < 10) {
                            if (data2.type == 1) {
                                itemup = ItemTemplate.itemDefault(idI);
                                itemup.sys = GameSrc.SysClass(data2.nclass);
                            } else {
                                itemup.sys = (byte) Util.nextInt(1, 3);
                                itemup = ItemTemplate.itemDefault((int) idI, itemup.sys);
                            }
                        } else {
                            itemup = ItemTemplate.itemDefault(idI);
                        }
                        p.c.addItemBag(false, itemup);
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    //
                    case 994: {
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
                            return;
                        }
                        if (p.c.level < 120) {
                            p.sendAddchatYellow("Trình độ của bạn không đủ để sử dụng vật phẩm này.");
                            return;
                        }
                        short idI = UseItem.vk12x[Util.nextInt(UseItem.vk12x.length)];
                        ItemTemplate data2 = ItemTemplate.ItemTemplateId(idI);
                        Item itemup = ItemTemplate.itemDefault(idI);
                        if (data2.type < 10) {
                            if (data2.type == 1) {
                                itemup = ItemTemplate.itemDefault(idI);
                                itemup.sys = GameSrc.SysClass(data2.nclass);
                            } else {
                                itemup.sys = (byte) Util.nextInt(1, 3);
                                itemup = ItemTemplate.itemDefault((int) idI, itemup.sys);
                            }
                        } else {
                            itemup = ItemTemplate.itemDefault(idI);
                        }
                        p.c.addItemBag(false, itemup);
                        p.c.removeItemBag(index, 1);
                        break;
                    } 
                    case 995: {
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
                            return;
                        }
                        if (p.c.level < 120) {
                            p.sendAddchatYellow("Trình độ của bạn không đủ để sử dụng vật phẩm này.");
                            return;
                        }
                        short idI = UseItem.tb12x[Util.nextInt(UseItem.tb12x.length)];
                        ItemTemplate data2 = ItemTemplate.ItemTemplateId(idI);
                        Item itemup = ItemTemplate.itemDefault(idI);
                        if (data2.type < 10) {
                            if (data2.type == 1) {
                                itemup = ItemTemplate.itemDefault(idI);
                                itemup.sys = GameSrc.SysClass(data2.nclass);
                            } else {
                                itemup.sys = (byte) Util.nextInt(1, 3);
                                itemup = ItemTemplate.itemDefault((int) idI, itemup.sys);
                            }
                        } else {
                            itemup = ItemTemplate.itemDefault(idI);
                        }
                        p.c.addItemBag(false, itemup);
                        p.c.removeItemBag(index, 1);
                        break;
                    } 
                     case 957: {                       
                        if (numbagnull < 1) {
                            p.conn.sendMessageLog("Hành trang không đủ chỗ trống.");
                            return;
                        }
                        if (p.c.level < 70) {
                            p.sendAddchatYellow("Trình độ yêu cầu cấp 70.");
                            return;
                        }
                        p.c.removeItemBag(index, 1);                                             
                        int itemID = RandomItem.RUONG_LANG_CO.next();
                        //short idI = UseItem.tuinew[Util.nextInt(UseItem.tuinew.length)];
                        Item itemup = ItemTemplate.itemDefault(itemID);
                      //  p.updateExp(2500000L);
                     //   p.c.pointBanhChung +=1;
                        itemup.isLock = item.isLock;
                        p.c.addItemBag(false, itemup);
                        break;
                        }
                   
                    case 598: { // cá
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành Trang Không Đủ Chỗ Trống");
                            return;
                        }
                        if (p.c.quantityItemyTotal(598) < 10) {
                            p.conn.sendMessageLog("Bạn Cần 10 Huyết long ngư ");
                            return;
                        } else {
                            final Item Itemup = ItemTemplate.itemDefault(599);
                            Itemup.isLock = item.isLock;
                            p.c.addItemBag(true, Itemup);
                        }
                        p.c.removeItemBags(item.id, 10);
                        break;
                    }
                    case 599: { //cá
                        if (numbagnull == 0) {
                            p.conn.sendMessageLog("Hành Trang Không Đủ Chỗ Trống");
                            return;
                        }
                        if (p.c.quantityItemyTotal(599) < 10) {
                            p.conn.sendMessageLog("Bạn Cần 10 Huyết sa ngư ");
                            return;
                        } else {
                            final Item Itemup = ItemTemplate.itemDefault(600);
                            Itemup.isLock = item.isLock;
                            p.c.addItemBag(true, Itemup);
                        }
                        p.c.removeItemBags(item.id, 10);
                        break;
                    }
                    case 695:
                    case 696:
                    case 697:
                    case 698:
                    case 699:
                    case 700:
                    case 701:
                    case 702:
                    case 703:
                    case 704: {
                        if (p.c.isNhanban) {
                            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.getBagNull() < 1) {
                            p.sendAddchatYellow(Language.NOT_ENOUGH_BAG);
                            return;
                        }
                        if (item.id == 704) {
                            p.sendAddchatYellow("Vật phẩm đã đạt cấp độ tối đa.");
                            return;
                        }
                        if (item.quantity < 10) {
                            p.sendAddchatYellow("Bạn cần đủ 10 viên đá để nâng cấp.");
                            return;
                        }
                        int quantity = item.quantity;
                        int plus = item.quantity / 10;
                        p.c.removeItemBag((byte) index, quantity - quantity % 10);
                        Item itemUp = ItemTemplate.itemDefault(item.id + 1, item.isLock);
                        itemUp.quantity = plus;
                        p.c.addItemBag(true, itemUp);
                        break;
                    }
                    case 705: {
                        if (p.c.isNhanban) {
                            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.useDanhVongPhu == 0) {
                            p.conn.sendMessageLog("Số lần sử dụng Danh vọng phú của bạn hôm nay đã hết.");
                            return;
                        }
                        p.c.useDanhVongPhu--;
                        p.c.countTaskDanhVong += 5;
                        p.sendAddchatYellow("Số lần nhận nhiệm vụ Danh vọng tăng thêm 5 lần");
                        p.c.removeItemBag(index, 1);
                        break;
                    }
                    case 733:
                    case 734:
                    case 735:
                    case 736:
                    case 737:
                    case 738:
                    case 739:
                    case 740:
                    case 741: {
                        if (p.c.isNhanban) {
                            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.gender == 0) {
                            p.sendAddchatYellow("Giới tính không phù hợp.");
                            return;
                        }
                        int checkID = item.id - 733;
                        if (p.c.quantityItemyTotal(item.id) < 1000) {
                            p.sendAddchatYellow("Bạn không đủ 1000 mảnh để ghép.");
                            return;
                        }
                        p.c.addItemBag(true, ItemTemplate.itemDefault(ItemTemplate.checkIdJiraiNam(checkID)));
                        p.c.removeItemBags(item.id, 1000);
                        break;
                    }
                    case 760:
                    case 761:
                    case 762:
                    case 763:
                    case 764:
                    case 765:
                    case 766:
                    case 767:
                    case 768: {
                        if (p.c.isNhanban) {
                            p.sendAddchatYellow(Language.NOT_FOR_PHAN_THAN);
                            return;
                        }
                        if (p.c.gender == 1) {
                            p.sendAddchatYellow("Giới tính không phù hợp.");
                            return;
                        }
                        int checkID = item.id - 760;
                        if (p.c.quantityItemyTotal(item.id) < 1000) {
                            p.sendAddchatYellow("Bạn không đủ 1000 mảnh để ghép.");
                            return;
                        }
                        p.c.addItemBag(true, ItemTemplate.itemDefault(ItemTemplate.checkIdJiraiNu(checkID)));
                        p.c.removeItemBags(item.id, 1000);
                        break;
                    }
                    case 743: {
                        if (Server.manager.event != 3) { // event noel
                            p.sendAddchatYellow(Language.END_EVENT);
                            return;
                        }
                        if (p.c.level < 40) {
                            p.conn.sendMessageLog("Trình độ của bạn không đủ để sử dụng vật phẩm này.");
                            return;
                        }
                        if (p.c.tileMap.map.getXHD() != -1 || p.c.mapid == 111 || p.c.mapid == 133 || p.c.tileMap.map.mapChienTruong()) {
                            p.conn.sendMessageLog("Bạn không thể sử dụng vật phẩm này tại đây.");
                            return;
                        }
                        BossTuanLoc bossTuanLoc = new BossTuanLoc(p.c.level);
                        if (bossTuanLoc != null && bossTuanLoc.map[0] != null && bossTuanLoc.map[0].area[0] != null) {
                            p.c.removeItemBag(index, 1);
                            p.c.tileMap.leave(p);
                            bossTuanLoc.map[0].area[0].EnterMap0(p.c);
                        }
                        break;
                    }
                    case ItemName.BANH_CHUNG:
                        ItemHandleUseEvent.BanhChung(p, item, index);
                        break;
                    case ItemName.BANH_TET:
                        ItemHandleUseEvent.BanhTet(p, item, index);
                        break;
                    case ItemName.TRANG_PHAO:
                        ItemHandleUseEvent.TrangPhao(p, item, index);
                        break;
                    default: {
                        break;
                    }
                }
                return;
            }
            if (ItemTemplate.checkIdNewItems(item.id)) {
                if (ItemTemplate.checkIdNewWP(item.id) != -1) {
                    p.c.get().ID_WEA_PONE = ItemTemplate.idNewItemWP[1][ItemTemplate.checkIdNewWP(item.id)];
                } else if (ItemTemplate.checkIdNewMatNa(item.id) != -1) {
                    p.c.get().ID_MAT_NA = ItemTemplate.idNewItemMatNa[1][ItemTemplate.checkIdNewMatNa(item.id)];
                } else if (ItemTemplate.checkIdNewMounts(item.id) != -1) {
                    p.c.get().ID_HORSE = ItemTemplate.idNewItemMounts[1][ItemTemplate.checkIdNewMounts(item.id)];
                } else if (ItemTemplate.checkIdNewBienHinh(item.id) != -1) {
                    p.c.get().ID_Bien_Hinh = ItemTemplate.idNewItemBienHinh[1][ItemTemplate.checkIdNewBienHinh(item.id)];
                } else if (ItemTemplate.checkIdNewCaiTrang(item.id) != -1) {
                    p.c.get().ID_HAIR = ItemTemplate.idNewItemCaiTrang[1][ItemTemplate.checkIdNewCaiTrang(item.id)];
                    p.c.get().ID_Body = ItemTemplate.idNewItemCaiTrang[2][ItemTemplate.checkIdNewCaiTrang(item.id)];
                    p.c.get().ID_LEG = ItemTemplate.idNewItemCaiTrang[3][ItemTemplate.checkIdNewCaiTrang(item.id)];
                }
                p.sendInfoMeNewItem();
            } else if (ItemTemplate.checkIdNewYoroi(item.id) != -1) {
                p.c.get().ID_PP = ItemTemplate.idNewItemYoroi[1][ItemTemplate.checkIdNewYoroi(item.id)];
                p.sendInfoMeNewItem();
            }
        } catch (Exception e) {
            e.printStackTrace();
        } finally {
            if (m != null) {
                m.cleanup();
            }
        }
    }

    public static void useItemChangeMap(Player p, Message m) {
        try {
            byte indexUI = m.reader().readByte();
            byte indexMenu = m.reader().readByte();
            m.cleanup();
            Item item = p.c.ItemBag[indexUI];
            if (item != null && (item.id == 37 || item.id == 35)) {
                if (item.id != 37) {
                    p.c.removeItemBag(indexUI);
                }
                if (p.c.mapid == 111 || p.c.mapid == 133) {
                    p.sendAddchatYellow("Không thể sử dụng vật phẩm này tại đây");
                    return;
                }
                if (indexMenu == 0 || indexMenu == 1 || indexMenu == 2) {
                    Map ma = Manager.getMapid(Map.arrTruong[indexMenu]);
                    if (ma == null) {
                        return;
                    }
                    for (TileMap area : ma.area) {
                        if (area.numplayers < ma.template.maxplayers) {
                            p.c.tileMap.leave(p);
                            area.EnterMap0(p.c);
                            return;
                        }
                    }
                }
                if (indexMenu == 3 || indexMenu == 4 || indexMenu == 5 || indexMenu == 6 || indexMenu == 7 || indexMenu == 8 || indexMenu == 9) {
                    Map ma = Manager.getMapid(Map.arrLang[indexMenu - 3]);
                    if (ma == null) {
                        return;
                    }
                    for (TileMap area : ma.area) {
                        if (area.numplayers < ma.template.maxplayers) {
                            p.c.tileMap.leave(p);
                            area.EnterMap0(p.c);
                            return;
                        }
                    }
                }
            }
            p.c.get().upDie();
        } catch (IOException ex) {
        } finally {
            if (m != null) {
                m.cleanup();
            }
        }

    }
}
