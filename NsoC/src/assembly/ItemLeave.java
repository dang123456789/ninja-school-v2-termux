package assembly;

import Item.ItemName;
import ItemLeave.LangCo;
import ItemLeave.LangHuyenThoai;
import ItemLeave.LangTruyenThuyet;
import ItemLeave.VDMQ;
import io.Util;

/**
 *
 * @author Võ Quang Huy
 */
public class ItemLeave {

    public static short[] ItemTrangBi3x = new short[]{
        96, 101, 106, 111, 116, 121,
        128, 129, 176, 138, 139, 181, 148, 149, 186, 158, 159, 191, 168, 169
    };
    public static short[] ItemTrangBi4x = new short[]{
        171, 161, 151, 141, 131,
        170, 160, 150, 140, 130,
        97, 102, 107, 112, 117, 122,
        192, 187, 182, 177
    };
    public static short[] ItemTrangBi5x = new short[]{
        173, 163, 153, 143, 133,
        172, 162, 152, 142, 132,
        98, 103, 108, 113, 118, 123,
        193, 188, 183, 178

    };
    public static short[] ItemTrangBi6x = new short[]{
        330, 329, 328, 327, 326,
        325, 323, 321, 319, 317,
        331, 332, 333, 334, 335, 336,
        324, 322, 320, 318
    };
    public static short[] ItemTrangBi7x = new short[]{
        363, 361, 359, 357, 355,
        368, 367, 366, 365, 364,
        369, 370, 371, 372, 373, 374,
        362, 360, 358, 356
    };
    public static short[] ItemTrangBi8x = new short[]{
        506, 507, 508, 509, 510, 511,
        500, 501, 502, 496, 497, 503, 498, 499, 504, 494, 495, 505, 492, 493

    };

    public static short[] SVC8x = new short[]{552, 553, 554, 555, 556, 557};
    public static short[] SVC10x = new short[]{558, 559, 560, 561, 562, 563};
    public static short[] VuKhi5x = new short[]{98, 103, 108, 113, 118, 123};
    public static short[] VuKhi6x = new short[]{331, 332, 333, 334, 335, 336};
    public static short[] VuKhi7x = new short[]{369, 370, 371, 372, 373, 374};
    public static short[] VuKhi8x = new short[]{506, 507, 508, 509, 510, 511};
    public static short[] VuKhi9x = new short[]{632, 633, 634, 635, 636, 637};
    public static short[] SVC12x = new short[]{941, 942, 943, 944, 945, 946};
    //------------------------------------------------------------------------//
    public static short[] TrangBiXeSoi = new short[]{439, 486, 440, 487, 441, 488, 442, 489}; // trang bị sói xe
    public static short[] ExpXeSoi = new short[]{449, 450, 451, 452, 453, 573, 574, 575, 576, 577, 578, 778, 275, 276, 277, 278}; // exp xe sói
    public static short[] arrItemmapngoai = new short[]{10000, 10000, 10000, 10000, 10001, 10001, 10001, 10001, 10002, 10002, 10002, 10002, 10003};

    //-------------------------------------------------------------------------//
    public static short[] ItemEvent = new short[]{590, 595};

    public static short[] NLSKHe = new short[]{428, 429, 430, 431};
    public static short[] arrItemSuKienTet1 = new short[]{428, 429, 430, 431};
    public static short[] ItemSuKienTrungThu = new short[]{292, 293, 294, 295, 296, 297};
    public static short[] arrItemSuKienNoel = new short[]{666, 667, 668};
    public static short[] arrItemSuKienTet = new short[]{638, 639, 641, 642};
    public static short[] MiengDuaHau = new short[]{ItemName.MIENG_DUA_HAU};
    public static short[] hoahong = new short[]{386, 387, 388};
    public static short[] dottre = new short[]{590, 591};
    public static short[] bong = new short[]{782, 783};
    public static short[] itemsukien = new short[]{527, 528, 529, 530};
    public static short[] arrItemSuKienHalloween = new short[]{609, 610, 607, 608};
    public static short[] NLSKVULAN = new short[]{790, 790, 791};
    public static short[] itembosstuonggiac = new short[]{
        12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, // yên
    };
    public static short[] NLXD = new short[]{887,888,889,890,891,892};

    public static short[] ItemBOSSThuong = new short[]{
        9, 9, 9, 9, 9, 9, 9, 9, 9, 9,
        454, 455, 455, 456, 457, // Chuyển Tinh Thạch , Tử Tinh Thạch
        436, 436, 436, 437, 437, 438, // Thẻ Bài Kinh Nghiệm Gia Tộc
        733, 734, 735, 736, 737, 738, 739, 740, 741, // Mảnh Jirai
        760, 761, 762, 763, 764, 765, 766, 767, 768,
        251, 251, 383, 547,837,840, // Giấy Vụn

        539,564, 991, 992, 993, 996,1011, 980, 981, 982, 983, 984, 985, 251 //X3
    };
    public static short[] ItemBOSSLC1 = new short[]{
        9, 9, 9, 9, 9, 9, 9, 9, 9, 9,
        454, 454, 455, 455, 455, 455, 563, 562, 561, 560, // Chuyển Tinh Thạch , Tử Tinh Thạch
        436, 436, 436, 436, 437, 437, 437, 438, 438, // Thẻ Bài Kinh Nghiệm Gia Tộc
        733, 734, 735, 736, 737, 738, 739, 740, 741, // Mảnh Jirai
        760, 761, 762, 763, 764, 765, 766, 767, 768,
        251, 251, 559, 558, 547,// Giấy Vụn
        539,564,//X3
        957, 957, 957, 957, 957,1011, 991, 992, 986, 993,1011, 980, 981, 982, 983, 984, 985, // RƯƠNG LÀNG CỔ
    };
    public static short[] ItemBOSSVDMQ = new short[]{
        9, 9, 9, 9, 9, 9, 9, 9, 9, 9,
        454, 454, 455, 455, 455, 455, 563, 562, 561, 560, // Chuyển Tinh Thạch , Tử Tinh Thạch
        436, 436, 436, 436, 437, 437, 437, 438, 438, // Thẻ Bài Kinh Nghiệm Gia Tộc
        733, 734, 735, 736, 737, 738, 739, 740, 741, // Mảnh Jirai
        760, 761, 762, 763, 764, 765, 766, 767, 768, 1011, 1011, 1011, 1011, 251, 251, 251, 251,
        251, 251, 559, 558, 547, 837,840,867,868,869,870,871,872,872,874,875,876,881,882,883,884,885,886,// Giấy Vụn
        539,564, 991, 992, 993, 996, 1011, 980, 981, 982, 983, 984, 985, 1011//X3
    };
    public static short[] ItemBOSSSuKien = new short[]{
        454, 454, 455, 455, 455, 455, 455, 456, 456, 456, 1011, 980, 981, 982, 983, 984, 985, 457, 457, 383, 384, 991, 992, 993, 996 // Chuyển Tinh Thạch , Tử Tinh Thạch  
    // DDV
    };
    public static short[] ItemLDGT = new short[]{
        9, 9, 9, 9, 9, 9, 9, 9, 9, 9,
        454, 454, 454, 455, 455, 455, 455, 455, 456, 456, 456, 457, 457, 457, // Chuyển Tinh Thạch , Tử Tinh Thạch
        436, 436, 436, 436, 436, 437, 437, 437, 438, 438, // Thẻ Bài Kinh Nghiệm Gia Tộc
        436, 436, 436, 436, 436, 437, 437, 437, 438, 438, // Thẻ Bài Kinh Nghiệm Gia Tộc
        251, 251, 251, // Giấy Vụn
        539,564,877,878,879,880, 986, 991, 992, 993, 1011, 1011, 980, 981, 982, 983, 984, 985, //X3
    };

    public static void randomLeave(TileMap place, Mob mob3, int master, int per, int map) {
        switch (per) {
            case 1: {
                switch (map) {
                    case 0:
                        VDMQ.LeaveExpXeSoiVDMQ(place, mob3, master); // VDMQ
                        break;
                    case 1:
                        LangCo.LeaveExpXeSoiLangCo(place, mob3, master); // LÀNG CỔ
                        break;
                    case 2:
                        LangHuyenThoai.LeaveExpXeSoiLangHuyenThoai(place, mob3, master); // MAP MỚI
                        break;
                    case 3:
                        LangTruyenThuyet.LeaveExpXeSoiLangTruyenThuyet(place, mob3, master); // MAP MỚI
                        break;
                    case 4:
                        LangCo.LeaveExpXeSoiLangCo(place, mob3, master); // MAP MỚI
                        break;
                    default:
                        break;
                }
                break;
            }
            case 2: {
                switch (map) {
                    case 0:
                        VDMQ.LeaveTuTinhThachVDMQ(place, mob3, master); // VDMQ
                        break;
                    case 1:
                        LangCo.LeaveTuTinhThachLangCo(place, mob3, master); // LÀNG CỔ
                        break;
                    case 2:
                        LangHuyenThoai.LeaveTuTinhThachLangHuyenThoai(place, mob3, master); // MAP MỚI   
                        break;
                    case 3:
                        LangTruyenThuyet.LeaveTuTinhThachLangTruyenThuyet(place, mob3, master); // MAP MỚI   
                        break;
                    case 4:
                        LangCo.LeaveTuTinhThachLangCo(place, mob3, master); // MAP MỚI   
                        break;
                    default:
                        break;
                }
                break;
            }
            case 3: {
                switch (map) {
                    case 0:
                        // VDMQ
                        VDMQ.LeaveTrangBiXeSoiVDMQ(place, mob3, master);
                        break;
                    case 1:
                        // LÀNG CỔ
                        LangCo.LeaveTrangBiXeSoiLangCo(place, mob3, master); // nhặt exp
                        break;
                    case 2:
                        // MAP MỚI
                        LangHuyenThoai.LeaveTrangBiXeSoiLangHuyenThoai(place, mob3, master);
                        break;
                    case 3:
                        // MAP MỚI
                        LangTruyenThuyet.LeaveTrangBiXeSoiLangTruyenThuyet(place, mob3, master);
                        break;
                    case 4:
                        // MAP MỚI
                        LangCo.LeaveTrangBiXeSoiLangCo(place, mob3, master); // nhặt exp
                        break;
                    default:
                        break;
                }
                break;
            }
            default: {
                break;
            }
        }
    }

    public static void leaveYen(TileMap place, Mob mob3, int master) {
        try {
            ItemMap im = place.LeaveItem((short) 12, mob3.x, mob3.y, mob3.templates.type, mob3.isboss);
            if (im != null) {
                im.item.quantity = 1;
                im.item.isLock = false;
                im.master = master;
                im.checkMob = mob3.lvboss;
                if (mob3.isboss) {
                    im.checkMob = 4;
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public static void leaveChiaKhoa(TileMap place, Mob mob3, int master) {
        try {
            ItemMap im = place.LeaveItem((short) 260, mob3.x, mob3.y, mob3.templates.type, mob3.isboss);
            if (im != null) {
                im.item.quantity = 1;
                im.item.isLock = true;
                im.master = master;
                im.item.isExpires = true;
                im.item.expires = place.map.timeMap;
                im.checkMob = mob3.lvboss;
                if (mob3.isboss) {
                    im.checkMob = 4;
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public static void leaveLDGT(TileMap place, Mob mob3, int master) { // nhặt đồ ldgt
        ItemMap im = null;
        try {
            if (mob3.templates.id == 81) {
                int per = Util.nextInt(10);
                if (per < 4) {
                    im = place.LeaveItem((short) 261, mob3.x, mob3.y, mob3.templates.type, mob3.isboss);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = true;
                        im.master = master;
                        im.item.isExpires = true;
                        im.item.expires = place.map.timeMap;
                    }
                }
            } else if (mob3.templates.id == 82) {
                int i;
                for (i = 0; i < ItemLDGT.length; i++) {
                    im = place.LeaveItem((short) ItemLDGT[i], mob3.x, mob3.y, mob3.templates.type, true);
                    if (im != null) {
                        im.item.quantity = 1;
                        im.item.isLock = false;
                        im.master = master;
                    }
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
    }
    public static void randomLeavenlxd(TileMap place, Mob mob3, int master, int per, int map) {
        if (per == 1 && map == 0) {
            Leavenlxd(place, mob3, master);
        }
    }
    
    public static void Leavenlxd(TileMap place, Mob mob3, int master) {
        ItemMap im = null;
        try {
            int random = Util.nextInt(1000);
            if (random < 1) {
                im = place.LeaveItem(NLXD[Util.nextInt(NLXD.length)], mob3.x, mob3.y, mob3.templates.type, false);
            }
        } catch (Exception e) {
        }
        if (im != null) {
            im.item.isLock = false;
            im.item.quantity = 1;
            im.master = master;
        }
    }
    // Set Item Vào Boss

}